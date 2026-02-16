import 'package:get/get.dart';
import '../../data/models/transaction_model.dart';
import '../../data/repositories/user_repository.dart';

class TransactionsController extends GetxController {
  final UserRepository repository;

  TransactionsController({required this.repository});

  final isLoading = false.obs;
  final transactions = <TransactionModel>[].obs;
  final selectedFilter = 'all'.obs;

  @override
  void onInit() {
    super.onInit();
    fetchTransactions();
  }

  Future<void> fetchTransactions() async {
    try {
      isLoading.value = true;

      final data = await repository.getUserTransactions('user_123');
      transactions.value = data;

    } catch (e) {
      Get.snackbar('error'.tr, e.toString(), snackPosition: SnackPosition.BOTTOM);
    } finally {
      isLoading.value = false;
    }
  }

  List<TransactionModel> get filteredTransactions {
    if (selectedFilter.value == 'all') {
      return transactions;
    }
    return transactions.where((t) => t.type == selectedFilter.value).toList();
  }

  void setFilter(String filter) {
    selectedFilter.value = filter;
  }

  @override
  Future<void> refresh() async {
    await fetchTransactions();
  }

  Future<void> onRefresh() async {
    await fetchTransactions();
  }

  List<TransactionModel> _getMockTransactions() {
    return [
      TransactionModel(
        id: '1',
        userId: 'user1',
        propertyId: '1',
        propertyName: 'برج الرياض التجاري',
        type: 'purchase',
        shares: 100,
        amount: 100000,
        pricePerShare: 1000,
        fee: 500,
        status: 'completed',
        createdAt: DateTime.now().subtract(const Duration(days: 90)),
        completedAt: DateTime.now().subtract(const Duration(days: 90)),
      ),
      TransactionModel(
        id: '2',
        userId: 'user1',
        propertyId: '1',
        propertyName: 'برج الرياض التجاري',
        type: 'dividend',
        amount: 500,
        status: 'completed',
        createdAt: DateTime.now().subtract(const Duration(days: 30)),
        completedAt: DateTime.now().subtract(const Duration(days: 30)),
        notes: 'Monthly dividend payment',
      ),
      TransactionModel(
        id: '3',
        userId: 'user1',
        propertyId: '2',
        propertyName: 'مجمع الفلل السكنية',
        type: 'purchase',
        shares: 50,
        amount: 42500,
        pricePerShare: 850,
        fee: 250,
        status: 'completed',
        createdAt: DateTime.now().subtract(const Duration(days: 60)),
        completedAt: DateTime.now().subtract(const Duration(days: 60)),
      ),
      TransactionModel(
        id: '4',
        userId: 'user1',
        propertyId: '3',
        propertyName: 'مركز التسوق الحديث',
        type: 'purchase',
        shares: 75,
        amount: 90000,
        pricePerShare: 1200,
        fee: 450,
        status: 'completed',
        createdAt: DateTime.now().subtract(const Duration(days: 45)),
        completedAt: DateTime.now().subtract(const Duration(days: 45)),
      ),
      TransactionModel(
        id: '5',
        userId: 'user1',
        propertyId: '0',
        propertyName: 'Wallet Deposit',
        type: 'deposit',
        amount: 50000,
        status: 'completed',
        createdAt: DateTime.now().subtract(const Duration(days: 100)),
        completedAt: DateTime.now().subtract(const Duration(days: 100)),
      ),
      TransactionModel(
        id: '6',
        userId: 'user1',
        propertyId: '2',
        propertyName: 'مجمع الفلل السكنية',
        type: 'dividend',
        amount: 400,
        status: 'completed',
        createdAt: DateTime.now().subtract(const Duration(days: 20)),
        completedAt: DateTime.now().subtract(const Duration(days: 20)),
      ),
    ];
  }
}
