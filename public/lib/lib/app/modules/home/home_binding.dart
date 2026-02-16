import 'package:get/get.dart';
import '../../data/providers/property_provider.dart';
import '../../data/providers/user_provider.dart';
import '../../data/repositories/property_repository.dart';
import '../../data/repositories/user_repository.dart';
import 'home_controller.dart';

class HomeBinding extends Bindings {
  @override
  void dependencies() {
    // Providers
    Get.lazyPut<PropertyProvider>(() => PropertyProvider());
    Get.lazyPut<UserProvider>(() => UserProvider());

    // Repositories
    Get.lazyPut<PropertyRepository>(() => PropertyRepository(Get.find()));
    Get.lazyPut<UserRepository>(() => UserRepository(Get.find()));

    // Controller
    Get.lazyPut<HomeController>(
      () => HomeController(
        propertyRepository: Get.find(),
        userRepository: Get.find(),
      ),
    );
  }
}
