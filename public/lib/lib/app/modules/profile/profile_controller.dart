import 'package:get/get.dart';
import '../../../services/theme_service.dart';
import '../../../services/translation_service.dart';
import '../../routes/app_pages.dart';

class ProfileController extends GetxController {
  final themeService = Get.find<ThemeService>();
  final translationService = Get.find<TranslationService>();

  final userName = 'محمد أحمد'.obs;
  final userEmail = 'mohammed@example.com'.obs;
  final isVerified = true.obs;

  void toggleTheme() {
    themeService.changeThemeMode();
  }

  void changeLanguage(String langCode) {
    translationService.changeLocale(langCode);
  }

  void logout() {
    Get.offAllNamed(Routes.LOGIN);
  }

  void editProfile() {
    // Navigate to edit profile
  }

  void changePassword() {
    // Navigate to change password
  }

  void verifyIdentity() {
    // Navigate to identity verification
  }

  void openPrivacyPolicy() {
    // Navigate to privacy policy
  }

  void openTermsConditions() {
    // Navigate to terms
  }

  void contactSupport() {
    // Navigate to support
  }
}
