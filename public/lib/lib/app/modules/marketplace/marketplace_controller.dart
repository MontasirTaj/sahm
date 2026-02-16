import 'package:flutter/material.dart';
import 'package:get/get.dart';
import '../../../services/haptic_service.dart';
import '../../data/models/marketplace_listing_model.dart';
import '../../data/repositories/marketplace_repository.dart';

class MarketplaceController extends GetxController with GetSingleTickerProviderStateMixin {
  final MarketplaceRepository repository;

  MarketplaceController({required this.repository});

  late TabController tabController;
  final isLoading = false.obs;
  final buyListings = <MarketplaceListingModel>[].obs;
  final sellListings = <MarketplaceListingModel>[].obs;
  final myListings = <MarketplaceListingModel>[].obs;

  @override
  void onInit() {
    super.onInit();
    tabController = TabController(length: 3, vsync: this);
    fetchMarketplaceData();
  }

  @override
  void onClose() {
    tabController.dispose();
    super.onClose();
  }

  Future<void> fetchMarketplaceData() async {
    try {
      isLoading.value = true;

      final listings = await repository.getListings();
      buyListings.value = listings;
      sellListings.value = listings;

      final userListings = await repository.getUserListings('user_123');
      myListings.value = userListings;

    } catch (e) {
      Get.snackbar('error'.tr, e.toString());
    } finally {
      isLoading.value = false;
    }
  }

  Future<void> purchaseListing(MarketplaceListingModel listing) async {
    try {
      await HapticService.lightImpact();
      final confirmed = await _showPurchaseDialog(listing);
      if (confirmed != true) return;

      await HapticService.mediumImpact();
      Get.dialog(const Center(child: CircularProgressIndicator()), barrierDismissible: false);

      final success = await repository.purchaseListing(listing.id, 'user_123');
      Get.back();

      if (success) {
        await HapticService.success();
        Get.snackbar('success'.tr, 'تم شراء الأسهم بنجاح', snackPosition: SnackPosition.BOTTOM);
        fetchMarketplaceData();
      }
    } catch (e) {
      Get.back();
      await HapticService.error();
      Get.snackbar('error'.tr, 'فشلت عملية الشراء', snackPosition: SnackPosition.BOTTOM);
    }
  }

  Future<void> cancelListing(String listingId) async {
    try {
      await HapticService.lightImpact();
      final confirmed = await _showCancelDialog();
      if (confirmed != true) return;

      Get.dialog(const Center(child: CircularProgressIndicator()), barrierDismissible: false);

      final success = await repository.cancelListing(listingId);
      Get.back();

      if (success) {
        await HapticService.success();
        Get.snackbar('success'.tr, 'تم إلغاء العرض بنجاح', snackPosition: SnackPosition.BOTTOM);
        fetchMarketplaceData();
      }
    } catch (e) {
      Get.back();
      await HapticService.error();
      Get.snackbar('error'.tr, 'فشل إلغاء العرض', snackPosition: SnackPosition.BOTTOM);
    }
  }

  Future<bool?> _showPurchaseDialog(MarketplaceListingModel listing) {
    return Get.dialog<bool>(
      AlertDialog(
        title: const Text('تأكيد الشراء'),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('هل تريد شراء ${listing.sharesAvailable} سهم من ${listing.propertyName}؟'),
            const SizedBox(height: 12),
            Text('المبلغ الإجمالي: ${listing.totalValue.toStringAsFixed(0)} ر.س',
              style: const TextStyle(fontWeight: FontWeight.bold)),
          ],
        ),
        actions: [
          TextButton(onPressed: () => Get.back(result: false), child: Text('cancel'.tr)),
          ElevatedButton(onPressed: () => Get.back(result: true), child: Text('confirm'.tr)),
        ],
      ),
    );
  }

  Future<bool?> _showCancelDialog() {
    return Get.dialog<bool>(
      AlertDialog(
        title: const Text('إلغاء العرض'),
        content: const Text('هل أنت متأكد من إلغاء هذا العرض؟'),
        actions: [
          TextButton(onPressed: () => Get.back(result: false), child: Text('cancel'.tr)),
          ElevatedButton(
            onPressed: () => Get.back(result: true),
            style: ElevatedButton.styleFrom(backgroundColor: Get.theme.colorScheme.error),
            child: const Text('إلغاء العرض'),
          ),
        ],
      ),
    );
  }

  List<MarketplaceListingModel> _getMockBuyListings() {
    return [
      MarketplaceListingModel(
        id: '1',
        sellerId: 'seller1',
        sellerName: 'أحمد محمد',
        propertyId: '1',
        propertyName: 'برج الرياض التجاري',
        propertyImage: 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab',
        sharesAvailable: 50,
        pricePerShare: 1050,
        totalValue: 52500,
        createdAt: DateTime.now().subtract(const Duration(days: 2)),
        status: 'active',
      ),
      MarketplaceListingModel(
        id: '2',
        sellerId: 'seller2',
        sellerName: 'فاطمة علي',
        propertyId: '2',
        propertyName: 'مجمع الفلل السكنية',
        propertyImage: 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6',
        sharesAvailable: 30,
        pricePerShare: 900,
        totalValue: 27000,
        createdAt: DateTime.now().subtract(const Duration(days: 1)),
        status: 'active',
      ),
    ];
  }

  List<MarketplaceListingModel> _getMockSellListings() {
    return [
      MarketplaceListingModel(
        id: '3',
        sellerId: 'seller3',
        sellerName: 'خالد عبدالله',
        propertyId: '3',
        propertyName: 'مركز التسوق الحديث',
        propertyImage: 'https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a',
        sharesAvailable: 75,
        pricePerShare: 1180,
        totalValue: 88500,
        createdAt: DateTime.now().subtract(const Duration(hours: 12)),
        status: 'active',
      ),
    ];
  }

  List<MarketplaceListingModel> _getMockMyListings() {
    return [
      MarketplaceListingModel(
        id: '4',
        sellerId: 'me',
        sellerName: 'محمد أحمد',
        propertyId: '1',
        propertyName: 'برج الرياض التجاري',
        propertyImage: 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab',
        sharesAvailable: 25,
        pricePerShare: 1080,
        totalValue: 27000,
        createdAt: DateTime.now().subtract(const Duration(days: 3)),
        status: 'active',
      ),
    ];
  }

  void onBuyShares(MarketplaceListingModel listing) {
    final sharesController = TextEditingController();

    Get.dialog(
      AlertDialog(
        title: Text('buy_shares'.tr),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Text(listing.propertyName),
            const SizedBox(height: 16),
            TextField(
              controller: sharesController,
              keyboardType: TextInputType.number,
              decoration: InputDecoration(
                labelText: 'number_of_shares'.tr,
                hintText: 'Max: ${listing.sharesAvailable}',
              ),
            ),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () => Get.back(),
            child: Text('cancel'.tr),
          ),
          ElevatedButton(
            onPressed: () {
              Get.back();
              Get.snackbar(
                'success'.tr,
                'purchase_successful'.tr,
                snackPosition: SnackPosition.BOTTOM,
              );
            },
            child: Text('confirm'.tr),
          ),
        ],
      ),
    );
  }

  void onCancelListing(MarketplaceListingModel listing) {
    Get.dialog(
      AlertDialog(
        title: Text('confirm'.tr),
        content: const Text('Are you sure you want to cancel this listing?'),
        actions: [
          TextButton(
            onPressed: () => Get.back(),
            child: Text('cancel'.tr),
          ),
          ElevatedButton(
            onPressed: () {
              myListings.remove(listing);
              Get.back();
              Get.snackbar(
                'success'.tr,
                'listing_cancelled'.tr,
                snackPosition: SnackPosition.BOTTOM,
              );
            },
            child: Text('confirm'.tr),
          ),
        ],
      ),
    );
  }

  Future<void> onRefresh() async {
    await fetchMarketplaceData();
  }
}
