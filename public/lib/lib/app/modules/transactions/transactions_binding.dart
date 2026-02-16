import 'package:get/get.dart';
import '../../data/providers/user_provider.dart';
import '../../data/repositories/user_repository.dart';
import 'transactions_controller.dart';

class TransactionsBinding extends Bindings {
  @override
  void dependencies() {
    Get.lazyPut<UserProvider>(() => UserProvider());
    Get.lazyPut<UserRepository>(() => UserRepository(Get.find()));
    Get.lazyPut<TransactionsController>(
      () => TransactionsController(repository: Get.find()),
    );
  }
}
