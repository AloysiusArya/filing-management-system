import 'dart:typed_data';
import 'package:flutter/material.dart';
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:flutter_web_browser/flutter_web_browser.dart';
import 'package:signature/signature.dart';
import 'package:path_provider/path_provider.dart';
import 'dart:async';
import 'dart:io';
import 'dart:ui' as ui;


void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        primarySwatch: Colors.blue,
        visualDensity: VisualDensity.adaptivePlatformDensity,
      ),
      home: LoginPage(),
    );
  }
}


class LoginPage extends StatefulWidget {
  @override
  _LoginPageState createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  final TextEditingController _usernameController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();

  void _login() {
    final username = _usernameController.text.trim();
    final password = _passwordController.text.trim();

    if (username == 'admin' && password == 'admin123') {
      Navigator.pushReplacement(
          context, MaterialPageRoute(builder: (context) => DashboardPage()));
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Invalid username or password')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Center(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(20),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: <Widget>[
              Icon(Icons.lock_outline, size: 100, color: Colors.blue),
              SizedBox(height: 40),
              TextField(
                controller: _usernameController,
                decoration: InputDecoration(
                  labelText: 'Username',
                  border: OutlineInputBorder(),
                  prefixIcon: Icon(Icons.person),
                ),
              ),
              SizedBox(height: 20),
              TextField(
                controller: _passwordController,
                obscureText: true,
                decoration: InputDecoration(
                  labelText: 'Password',
                  border: OutlineInputBorder(),
                  prefixIcon: const Icon(Icons.lock),
                ),
              ),
              const SizedBox(height: 40),
              ElevatedButton(
                style: ElevatedButton.styleFrom(
                  padding: const EdgeInsets.symmetric(horizontal: 50, vertical: 15),
                ),
                onPressed: _login,
                child: const Text('Login'),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class DashboardPage extends StatefulWidget {
  @override
  _DashboardPageState createState() => _DashboardPageState();
}

class _DashboardPageState extends State<DashboardPage> {
  late Future<List<Surat>> futureSurat;

  @override
  void initState() {
    super.initState();
    futureSurat = fetchSurat();
  }

  Future<List<Surat>> fetchSurat() async {
    final response = await http.get(Uri.parse('http://192.168.181.29:8888/filing_management_system/getSurat.php'));
    if (response.statusCode == 200) {
      List<dynamic> suratJson = json.decode(response.body);
      return suratJson.map((json) => Surat.fromJson(json)).toList();
    } else {
      throw Exception('Failed to load surat');
    }
  }

  Future<void> openBrowserTab(String url) async {
    await FlutterWebBrowser.openWebPage(url: url);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Dashboard'),
      ),
      body: FutureBuilder<List<Surat>>(
        future: futureSurat,
        builder: (context, snapshot) {
          if (snapshot.hasData) {
            List<Surat>? data = snapshot.data;
            return ListView.builder(
              itemCount: data?.length,
              itemBuilder: (context, index) {
                return ListTile(
                  title: Text(data![index].perihal),
                  subtitle: Text(data[index].tanggal),
                  trailing: IconButton(
                    icon: Icon(Icons.picture_as_pdf),
                    onPressed: () {
                      Navigator.of(context).push(MaterialPageRoute(
                        builder: (context) => SignaturePage(pdfFileName: data[index].filePdf),
                      ));
                    },
                  ),
                );
              },
            );
          } else if (snapshot.hasError) {
            return Text("${snapshot.error}");
          }
          return const CircularProgressIndicator();
        },
      ),
    );
  }
}

class Surat {
  final String id;
  final String nomorSurat;
  final String tujuanPermohonan;
  final String alamatTujuan;
  final String perihal;
  final String tanggal;
  final String divisi;
  final String filePdf;
  final String jenisSurat;
  final String tipeSurat;

  Surat({
    required this.id,
    required this.nomorSurat,
    required this.tujuanPermohonan,
    required this.alamatTujuan,
    required this.perihal,
    required this.tanggal,
    required this.divisi,
    required this.filePdf,
    required this.jenisSurat,
    required this.tipeSurat,
  });

  factory Surat.fromJson(Map<String, dynamic> json) {
    return Surat(
      id: json['id'],
      nomorSurat: json['nomor_surat'],
      tujuanPermohonan: json['tujuan_permohonan'],
      alamatTujuan: json['alamat_tujuan'],
      perihal: json['perihal'],
      tanggal: json['tanggal'],
      divisi: json['divisi'],
      filePdf: json['file_pdf'],
      jenisSurat: json['jenis_surat'],
      tipeSurat: json['tipe_surat'],
    );
  }
}

class SignaturePage extends StatefulWidget {
  final String pdfFileName; // Nama file PDF untuk ditandatangani

  SignaturePage({Key? key, required this.pdfFileName}) : super(key: key);

  @override
  _SignaturePageState createState() => _SignaturePageState();
}

class _SignaturePageState extends State<SignaturePage> {
  final SignatureController _controller = SignatureController(
    penStrokeWidth: 5,
    penColor: Colors.black,
    exportBackgroundColor: Colors.white,
  );

  Future<void> uploadSignature() async {
    if (_controller.isNotEmpty) {
      final Uint8List? data = await _controller.toPngBytes();
      if (data != null) {
        var request = http.MultipartRequest(
          'POST',
          Uri.parse('http://192.168.181.29:8888/filing_management_system/updateSurat.php'),
        );

        request.fields['pdfFileName'] = widget.pdfFileName; // Kirim nama file PDF
        request.files.add(
          http.MultipartFile.fromBytes(
            'signature', // Pastikan 'signature' sesuai dengan field yang diterima oleh PHP
            data,
            filename: 'signature.png', // Sesuaikan jika perlu
          ),
        );

        var response = await request.send();

        if (response.statusCode == 200) {
          ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text("Signature uploaded successfully")));
        } else {
          ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text("Failed to upload signature")));
        }
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Sign Document'),
      ),
      body: Column(
        children: <Widget>[
          Expanded(
            child: Signature(controller: _controller, backgroundColor: Colors.white),
          ),
          ElevatedButton(
            onPressed: () async {
              await uploadSignature();
              Navigator.of(context).pop(); // Kembali ke halaman sebelumnya setelah selesai
            },
            child: const Text('Submit Signature'),
          )
        ],
      ),
    );
  }

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }
}
