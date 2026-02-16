import 'package:get/get.dart';
import '../../data/models/portfolio_item_model.dart';
import '../../data/repositories/user_repository.dart';

class PortfolioController extends GetxController {
  final UserRepository repository;

  PortfolioController({required this.repository});

  final isLoading = false.obs;
  final portfolioItems = <PortfolioItemModel>[].obs;

  final totalPortfolioValue = 0.0.obs;
  final totalInvested = 0.0.obs;
  final totalProfitLoss = 0.0.obs;
  final profitPercentage = 0.0.obs;

  @override
  void onInit() {
    super.onInit();
    fetchPortfolio();
  }

  Future<void> fetchPortfolio() async {
    try {
      isLoading.value = true;

      final items = await repository.getUserPortfolio('user_123');
      portfolioItems.value = items;

      // Calculate totals
      totalInvested.value = portfolioItems.fold(
        0.0,
        (sum, item) => sum + item.totalInvested,
      );

      totalPortfolioValue.value = portfolioItems.fold(
        0.0,
        (sum, item) => sum + item.currentValue,
      );

      totalProfitLoss.value = totalPortfolioValue.value - totalInvested.value;

      if (totalInvested.value > 0) {
        profitPercentage.value = (totalProfitLoss.value / totalInvested.value) * 100;
      }

    } catch (e) {
      Get.snackbar('error'.tr, e.toString(), snackPosition: SnackPosition.BOTTOM);
    } finally {
      isLoading.value = false;
    }
  }

  @override
  Future<void> refresh() async {
    await fetchPortfolio();
  }

  List<PortfolioItemModel> _getMockPortfolio() {
    return [
      PortfolioItemModel(
        id: '1',
        propertyId: '1',
        propertyName: 'برج الرياض التجاري',
        propertyImage: 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab',
        propertyType: 'commercial',
        sharesOwned: 100,
        purchasePrice: 1000,
        currentPrice: 1050,
        totalInvested: 100000,
        currentValue: 105000,
        profitLoss: 5000,
        profitPercentage: 5.0,
        investmentDate: DateTime.now().subtract(const Duration(days: 90)),
        totalDividends: 2500,
        lastDividendAmount: 500,
        lastDividendDate: DateTime.now().subtract(const Duration(days: 30)),
      ),
      PortfolioItemModel(
        id: '2',
        propertyId: '2',
        propertyName: 'مجمع الفلل السكنية',
        propertyImage: 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6',
        propertyType: 'residential',
        sharesOwned: 50,
        purchasePrice: 850,
        currentPrice: 900,
        totalInvested: 42500,
        currentValue: 45000,
        profitLoss: 2500,
        profitPercentage: 5.88,
        investmentDate: DateTime.now().subtract(const Duration(days: 60)),
        totalDividends: 1200,
        lastDividendAmount: 400,
        lastDividendDate: DateTime.now().subtract(const Duration(days: 20)),
      ),
      PortfolioItemModel(
        id: '3',
        propertyId: '3',
        propertyName: 'مركز التسوق الحديث',
        propertyImage: 'https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a',
        propertyType: 'commercial',
        sharesOwned: 75,
        purchasePrice: 1200,
        currentPrice: 1180,
        totalInvested: 90000,
        currentValue: 88500,
        profitLoss: -1500,
        profitPercentage: -1.67,
        investmentDate: DateTime.now().subtract(const Duration(days: 45)),
        totalDividends: 3000,
        lastDividendAmount: 750,
        lastDividendDate: DateTime.now().subtract(const Duration(days: 15)),
      ),
    ];
  }

  void onPropertyTap(PortfolioItemModel item) {
    // Navigate to property details
  }

  Future<void> onRefresh() async {
    await fetchPortfolio();
  }
}
