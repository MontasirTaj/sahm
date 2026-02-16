import 'package:get/get.dart';
import '../../data/providers/property_provider.dart';
import '../../data/providers/user_provider.dart';
import '../../data/providers/marketplace_provider.dart';
import '../../data/repositories/property_repository.dart';
import '../../data/repositories/user_repository.dart';
import '../../data/repositories/marketplace_repository.dart';
import '../home/home_controller.dart';
import '../marketplace/marketplace_controller.dart';
import '../portfolio/portfolio_controller.dart';
import '../transactions/transactions_controller.dart';
import '../profile/profile_controller.dart';
import 'main_navigation_controller.dart';

class MainNavigationBinding extends Bindings {
  @override
  void dependencies() {
    // Providers - Initialize first as they're used by repositories
    Get.lazyPut<PropertyProvider>(() => PropertyProvider(), fenix: true);
    Get.lazyPut<UserProvider>(() => UserProvider(), fenix: true);
    Get.lazyPut<MarketplaceProvider>(() => MarketplaceProvider(), fenix: true);

    // Repositories - Initialize with providers
    Get.lazyPut<PropertyRepository>(() => PropertyRepository(Get.find()), fenix: true);
    Get.lazyPut<UserRepository>(() => UserRepository(Get.find()), fenix: true);
    Get.lazyPut<MarketplaceRepository>(() => MarketplaceRepository(Get.find()), fenix: true);

    // Main Navigation Controller
    Get.lazyPut<MainNavigationController>(
      () => MainNavigationController(),
    );

    // Child Page Controllers - Initialize all so they're ready when tabs switch
    Get.lazyPut<HomeController>(
      () => HomeController(
        propertyRepository: Get.find(),
        userRepository: Get.find(),
      ),
      fenix: true,
    );

    Get.lazyPut<MarketplaceController>(
      () => MarketplaceController(repository: Get.find()),
      fenix: true,
    );

    Get.lazyPut<PortfolioController>(
      () => PortfolioController(repository: Get.find()),
      fenix: true,
    );

    Get.lazyPut<TransactionsController>(
      () => TransactionsController(repository: Get.find()),
      fenix: true,
    );

    Get.lazyPut<ProfileController>(
      () => ProfileController(),
      fenix: true,
    );
  }
}
