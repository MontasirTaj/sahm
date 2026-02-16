import 'package:get/get.dart';
import 'package:get_storage/get_storage.dart';
import '../../routes/app_pages.dart';

class OnboardingController extends GetxController {
  final storage = GetStorage();

  void completeOnboarding() {
    storage.write('onboarding_completed', true);
    Get.offAllNamed(Routes.LOGIN);
  }

  void skipOnboarding() {
    storage.write('onboarding_completed', true);
    Get.offAllNamed(Routes.LOGIN);
  }

  static bool shouldShowOnboarding() {
    final storage = GetStorage();
    return storage.read('onboarding_completed') != true;
  }
}
