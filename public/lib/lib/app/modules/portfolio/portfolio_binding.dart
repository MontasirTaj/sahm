import 'package:get/get.dart';
import '../../data/providers/user_provider.dart';
import '../../data/repositories/user_repository.dart';
import 'portfolio_controller.dart';

class PortfolioBinding extends Bindings {
  @override
  void dependencies() {
    Get.lazyPut<UserProvider>(() => UserProvider());
    Get.lazyPut<UserRepository>(() => UserRepository(Get.find()));
    Get.lazyPut<PortfolioController>(
      () => PortfolioController(repository: Get.find()),
    );
  }
}
