import 'package:get/get.dart';
import '../../data/providers/marketplace_provider.dart';
import '../../data/repositories/marketplace_repository.dart';
import 'marketplace_controller.dart';

class MarketplaceBinding extends Bindings {
  @override
  void dependencies() {
    Get.lazyPut<MarketplaceProvider>(() => MarketplaceProvider());
    Get.lazyPut<MarketplaceRepository>(() => MarketplaceRepository(Get.find()));
    Get.lazyPut<MarketplaceController>(
      () => MarketplaceController(repository: Get.find()),
    );
  }
}
