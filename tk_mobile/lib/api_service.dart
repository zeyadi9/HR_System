import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class ApiService {
  // Base URL pointing to Laravel Server. 
  // 10.0.2.2 is the localhost address for Android Emulators.
  // Set this to your local server IP (e.g. 192.168.1.X) when testing on a physical device.
  static const String defaultBaseUrl = 'http://81.10.14.216:8080/api';
  
  static Future<String> getBaseUrl() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('api_base_url') ?? defaultBaseUrl;
  }

  static Future<void> setBaseUrl(String newUrl) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('api_base_url', newUrl);
  }

  // Get Auth Token
  static Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('auth_token');
  }

  // Get Auth Headers
  static Future<Map<String, String>> _getHeaders() async {
    final token = await getToken();
    return {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      if (token != null) 'Authorization': 'Bearer $token',
    };
  }

  // ========================================================
  // AUTHENTICATION & PROFILE
  // ========================================================

  static Future<Map<String, dynamic>> login(String email, String password) async {
    final baseUrl = await getBaseUrl();
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/login'),
        headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
        body: jsonEncode({'email': email, 'password': password}),
      );

      final data = jsonDecode(response.body);
      if (response.statusCode == 200 && data['success'] == true) {
        final prefs = await SharedPreferences.getInstance();
        await prefs.setString('auth_token', data['data']['token']);
        await prefs.setString('user_role', data['data']['user']['role']);
        await prefs.setString('user_name', data['data']['user']['name']);
        await prefs.setString('user_email', data['data']['user']['email']);
      }
      return data;
    } catch (e) {
      return {'success': false, 'message': 'تعذر الاتصال بالخادم: $e'};
    }
  }

  static Future<Map<String, dynamic>> register(
      String name, String email, String jobTitle, String password) async {
    final baseUrl = await getBaseUrl();
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/register'),
        headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
        body: jsonEncode({
          'name': name,
          'email': email,
          'job_title': jobTitle,
          'password': password,
        }),
      );
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'تعذر الاتصال بالخادم: $e'};
    }
  }

  static Future<Map<String, dynamic>> logout() async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.post(
        Uri.parse('$baseUrl/logout'),
        headers: headers,
      );

      final prefs = await SharedPreferences.getInstance();
      await prefs.remove('auth_token');
      await prefs.remove('user_role');
      await prefs.remove('user_name');
      await prefs.remove('user_email');

      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'تم تسجيل الخروج محلياً'};
    }
  }

  static Future<Map<String, dynamic>> changePassword(String currentPassword, String newPassword, String confirmPassword) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.post(
        Uri.parse('$baseUrl/change-password'),
        headers: headers,
        body: jsonEncode({
          'current_password': currentPassword,
          'new_password': newPassword,
          'new_password_confirmation': confirmPassword
        }),
      );
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> getCurrentUser() async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.get(Uri.parse('$baseUrl/user'), headers: headers);
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  // ========================================================
  // ATTENDANCE (حضور وانصراف)
  // ========================================================

  static Future<Map<String, dynamic>> getAttendance([String? periodStart]) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final query = periodStart != null ? '?period_start=$periodStart' : '';
      final response = await http.get(Uri.parse('$baseUrl/attendance$query'), headers: headers);
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> storeAttendance(String type) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.post(
        Uri.parse('$baseUrl/attendance'),
        headers: headers,
        body: jsonEncode({'type': type}),
      );
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  // ========================================================
  // OVERTIME (العمل الإضافي)
  // ========================================================

  static Future<Map<String, dynamic>> getOvertime([String? periodStart]) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final query = periodStart != null ? '?period_start=$periodStart' : '';
      final response = await http.get(Uri.parse('$baseUrl/overtime$query'), headers: headers);
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> storeOvertime(
      String date, String day, String reason, String from, String to) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.post(
        Uri.parse('$baseUrl/overtime'),
        headers: headers,
        body: jsonEncode({
          'date': date,
          'day': day,
          'reason': reason,
          'from': from,
          'to': to,
        }),
      );
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  // ========================================================
  // LEAVES (الإجازات)
  // ========================================================

  static Future<Map<String, dynamic>> getLeaves([String? periodStart]) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final query = periodStart != null ? '?period_start=$periodStart' : '';
      final response = await http.get(Uri.parse('$baseUrl/leaves$query'), headers: headers);
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> storeLeave(
      String date, String day, String reason, String substitute, int daysCount) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.post(
        Uri.parse('$baseUrl/leaves'),
        headers: headers,
        body: jsonEncode({
          'date': date,
          'day': day,
          'reason': reason,
          'substitute': substitute,
          'days_count': daysCount,
        }),
      );
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  // ========================================================
  // PERMISSIONS (الأذونات)
  // ========================================================

  static Future<Map<String, dynamic>> getPermissions([String? periodStart]) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final query = periodStart != null ? '?period_start=$periodStart' : '';
      final response = await http.get(Uri.parse('$baseUrl/permissions$query'), headers: headers);
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> storePermission(
      String date, String day, String reason, String permissionType, String? from, String? to) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.post(
        Uri.parse('$baseUrl/permissions'),
        headers: headers,
        body: jsonEncode({
          'date': date,
          'day': day,
          'reason': reason,
          'permission_type': permissionType,
          'from': from,
          'to': to,
        }),
      );
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  // ========================================================
  // PENALTIES & SETTLEMENTS & INCENTIVES
  // ========================================================

  static Future<Map<String, dynamic>> getPenalties([String? periodStart]) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final query = periodStart != null ? '?period_start=$periodStart' : '';
      final response = await http.get(Uri.parse('$baseUrl/penalties$query'), headers: headers);
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> getSettlements([String? periodStart]) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final query = periodStart != null ? '?period_start=$periodStart' : '';
      final response = await http.get(Uri.parse('$baseUrl/settlements$query'), headers: headers);
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> storeSettlement(String note) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.post(
        Uri.parse('$baseUrl/settlements'),
        headers: headers,
        body: jsonEncode({'note': note}),
      );
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> getIncentives([String? periodStart]) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final query = periodStart != null ? '?period_start=$periodStart' : '';
      final response = await http.get(Uri.parse('$baseUrl/incentives$query'), headers: headers);
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> getAdminNotes() async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.get(Uri.parse('$baseUrl/admin-notes'), headers: headers);
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  // ========================================================
  // ADMIN OPERATIONS
  // ========================================================

  static Future<Map<String, dynamic>> adminGetOvertimes() async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.get(Uri.parse('$baseUrl/admin/overtime'), headers: headers);
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> adminApproveOvertime(int id) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.post(Uri.parse('$baseUrl/admin/overtime/$id/accept'), headers: headers);
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> adminRejectOvertime(int id, String reason) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.post(
        Uri.parse('$baseUrl/admin/overtime/$id/refuse'),
        headers: headers,
        body: jsonEncode({'refuse_reason': reason}),
      );
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> adminGetLeaves() async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.get(Uri.parse('$baseUrl/admin/leaves'), headers: headers);
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> adminApproveLeave(int id) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.post(Uri.parse('$baseUrl/admin/leaves/$id/accept'), headers: headers);
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> adminRejectLeave(int id, String reason) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.post(
        Uri.parse('$baseUrl/admin/leaves/$id/refuse'),
        headers: headers,
        body: jsonEncode({'refuse_reason': reason}),
      );
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> adminGetPermissions() async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.get(Uri.parse('$baseUrl/admin/permissions'), headers: headers);
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> adminApprovePermission(int id) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.post(Uri.parse('$baseUrl/admin/permissions/$id/accept'), headers: headers);
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> adminRejectPermission(int id, String reason) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.post(
        Uri.parse('$baseUrl/admin/permissions/$id/refuse'),
        headers: headers,
        body: jsonEncode({'refuse_reason': reason}),
      );
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> adminGetAttendance() async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.get(Uri.parse('$baseUrl/admin/attendance'), headers: headers);
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> adminApproveAttendance(int id) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.post(Uri.parse('$baseUrl/admin/attendance/$id/accept'), headers: headers);
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> adminRejectAttendance(int id, String reason) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.post(
        Uri.parse('$baseUrl/admin/attendance/$id/refuse'),
        headers: headers,
        body: jsonEncode({'refuse_reason': reason}),
      );
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> adminCreatePenalty(
      int userId, String reason, double amount, String? notes) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.post(
        Uri.parse('$baseUrl/admin/penalties'),
        headers: headers,
        body: jsonEncode({
          'user_id': userId,
          'reason': reason,
          'amount': amount,
          'notes': notes,
        }),
      );
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  // ========================================================
  // SUPER ADMIN OPERATIONS
  // ========================================================

  static Future<Map<String, dynamic>> superAdminGetPenalties() async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.get(Uri.parse('$baseUrl/super-admin/penalties'), headers: headers);
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> superAdminApprovePenalty(int id) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.post(Uri.parse('$baseUrl/super-admin/penalties/$id/accept'), headers: headers);
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> superAdminRejectPenalty(int id, String reason) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.post(
        Uri.parse('$baseUrl/super-admin/penalties/$id/refuse'),
        headers: headers,
        body: jsonEncode({'refuse_reason': reason}),
      );
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> superAdminGetSettlements() async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.get(Uri.parse('$baseUrl/super-admin/settlements'), headers: headers);
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> superAdminApproveSettlement(int id, String? acceptNote) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.post(
        Uri.parse('$baseUrl/super-admin/settlements/$id/accept'),
        headers: headers,
        body: jsonEncode({'accept_note': acceptNote}),
      );
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> superAdminRejectSettlement(int id, String reason) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.post(
        Uri.parse('$baseUrl/super-admin/settlements/$id/refuse'),
        headers: headers,
        body: jsonEncode({'refuse_reason': reason}),
      );
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> superAdminManualEntry(
      String entryType, String employeeName, String date, String day, String reason, Map<String, dynamic> extraParams) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final body = {
        'entry_type': entryType,
        'employee_name': employeeName,
        'date': date,
        'day': day,
        'reason': reason,
        ...extraParams
      };
      final response = await http.post(
        Uri.parse('$baseUrl/super-admin/employee-entry'),
        headers: headers,
        body: jsonEncode(body),
      );
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> superAdminGetEmployeeProfiles() async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.get(Uri.parse('$baseUrl/super-admin/employee-profiles'), headers: headers);
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> superAdminGetProfileDetails(int id) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.get(Uri.parse('$baseUrl/super-admin/employee-profiles/$id'), headers: headers);
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> superAdminUpdateProfile(int id, String? jobTitle, double hourlyRate) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.post(
        Uri.parse('$baseUrl/super-admin/employee-profiles/$id/update'),
        headers: headers,
        body: jsonEncode({'job_title': jobTitle, 'hourly_rate': hourlyRate}),
      );
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> superAdminResetPassword(int id, String password, String confirmPassword) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.post(
        Uri.parse('$baseUrl/super-admin/employee-profiles/$id/reset-password'),
        headers: headers,
        body: jsonEncode({
          'password': password,
          'password_confirmation': confirmPassword,
        }),
      );
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> superAdminGetAuditLogs() async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.get(Uri.parse('$baseUrl/super-admin/audit-logs'), headers: headers);
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> superAdminGetFullReport([String? periodStart]) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final query = periodStart != null ? '?period_start=$periodStart' : '';
      final response = await http.get(Uri.parse('$baseUrl/super-admin/full-report$query'), headers: headers);
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> superAdminAddNote(int userId, String note) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.post(
        Uri.parse('$baseUrl/super-admin/notes'),
        headers: headers,
        body: jsonEncode({'user_id': userId, 'note': note}),
      );
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }

  static Future<Map<String, dynamic>> superAdminAddIncentive(int userId, String reason, double amount) async {
    final baseUrl = await getBaseUrl();
    try {
      final headers = await _getHeaders();
      final response = await http.post(
        Uri.parse('$baseUrl/super-admin/incentives'),
        headers: headers,
        body: jsonEncode({'user_id': userId, 'reason': reason, 'amount': amount}),
      );
      return jsonDecode(response.body);
    } catch (e) {
      return {'success': false, 'message': 'فشل الاتصال: $e'};
    }
  }
}
