import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:intl/intl.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:animate_do/animate_do.dart';
import '../../../core/theme/app_colors.dart';
import '../../data/models/marketplace_listing_model.dart';
import '../../widgets/marketplace_listing_card.dart';
import '../../widgets/shimmer_loading.dart';
import '../../widgets/empty_state.dart';
import 'marketplace_controller.dart';

class MarketplaceView extends GetView<MarketplaceController> {
  const MarketplaceView({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: Text('marketplace'.tr),
        bottom: TabBar(
          controller: controller.tabController,
          tabs: [
            Tab(text: 'buy_orders'.tr),
            Tab(text: 'sell_orders'.tr),
            Tab(text: 'my_listings'.tr),
          ],
        ),
      ),
      body: Obx(() {
        if (controller.isLoading.value) {
          return _buildShimmerLoading();
        }

        return TabBarView(
          controller: controller.tabController,
          children: [
            _buildListingsTab(controller.buyListings, false),
            _buildListingsTab(controller.sellListings, false),
            _buildListingsTab(controller.myListings, true),
          ],
        );
      }),
      floatingActionButton: FadeInUp(
        duration: const Duration(milliseconds: 600),
        child: FloatingActionButton.extended(
          onPressed: () {
            Get.snackbar(
              'قريباً',
              'سيتم إضافة هذه الميزة قريباً',
              snackPosition: SnackPosition.BOTTOM,
              duration: const Duration(seconds: 2),
            );
          },
          icon: const Icon(Icons.add),
          label: Text('create_listing'.tr),
        ),
      ),
    );
  }

  Widget _buildShimmerLoading() {
    return ListView.builder(
      itemCount: 5,
      itemBuilder: (context, index) => const MarketplaceListingShimmer(),
    );
  }

  Widget _buildListingsTab(List<MarketplaceListingModel> listings,
      bool isMyListings) {
    if (listings.isEmpty) {
      return EmptyState(
        icon: isMyListings ? Icons.inventory_2_outlined : Icons.store_outlined,
        title: isMyListings ? 'لا توجد قائمة' : 'لا توجد عروض',
        message: isMyListings
            ? 'لم تقم بإنشاء أي عرض بيع بعد. ابدأ بعرض أسهمك للبيع!'
            : 'لا توجد عروض متاحة حالياً. تحقق لاحقاً!',
        actionText: isMyListings ? 'إنشاء عرض' : null,
        onAction: isMyListings ? () {
          Get.snackbar('قريباً', 'سيتم إضافة هذه الميزة قريباً');
        } : null,
      );
    }

    return RefreshIndicator(
      onRefresh: controller.fetchMarketplaceData,
      child: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: listings.length,
        itemBuilder: (context, index) {
          final listing = listings[index];
          return FadeInUp(
            duration: Duration(milliseconds: 400 + (index * 100)),
            child: Padding(
              padding: const EdgeInsets.only(bottom: 16),
              child: MarketplaceListingCard(
                listing: listing,
                onBuy: isMyListings ? null : () =>
                    controller.purchaseListing(listing),
                onCancel: isMyListings ? () =>
                    controller.cancelListing(listing.id) : null,
                showActions: true,
              ),
            ),
          );
        },
      ),
    );
  }

  Widget _buildListingCard(MarketplaceListingModel listing, bool isMyListings) {
    final formatter = NumberFormat('#,##0', 'ar');
    final dateFormatter = DateFormat('dd/MM/yyyy HH:mm', 'ar');

    return Card(
      margin: const EdgeInsets.only(bottom: 16),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                ClipRRect(
                  borderRadius: BorderRadius.circular(8),
                  child: CachedNetworkImage(
                    imageUrl: listing.propertyImage,
                    width: 80,
                    height: 80,
                    fit: BoxFit.cover,
                    placeholder: (context, url) =>
                        Container(
                          color: AppColors.border,
                          child: const Center(
                              child: CircularProgressIndicator()),
                        ),
                    errorWidget: (context, url, error) =>
                        Container(
                          color: AppColors.border,
                          child: const Icon(Icons.error),
                        ),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        listing.propertyName,
                        style: const TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      const SizedBox(height: 4),
                      Text(
                        'Seller: ${listing.sellerName}',
                        style: const TextStyle(
                          fontSize: 13,
                          color: AppColors.textSecondary,
                        ),
                      ),
                      const SizedBox(height: 4),
                      Text(
                        dateFormatter.format(listing.createdAt),
                        style: const TextStyle(
                          fontSize: 12,
                          color: AppColors.textHint,
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            ),
            const SizedBox(height: 16),
            const Divider(),
            const SizedBox(height: 12),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'price_per_share'.tr,
                      style: const TextStyle(
                        fontSize: 12,
                        color: AppColors.textSecondary,
                      ),
                    ),
                    const SizedBox(height: 4),
                    Text(
                      '${formatter.format(listing.pricePerShare)} ${'currency'
                          .tr}',
                      style: const TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                        color: AppColors.primary,
                      ),
                    ),
                  ],
                ),
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'shares_for_sale'.tr,
                      style: const TextStyle(
                        fontSize: 12,
                        color: AppColors.textSecondary,
                      ),
                    ),
                    const SizedBox(height: 4),
                    Text(
                      formatter.format(listing.sharesAvailable),
                      style: const TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ],
                ),
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'total_amount'.tr,
                      style: const TextStyle(
                        fontSize: 12,
                        color: AppColors.textSecondary,
                      ),
                    ),
                    const SizedBox(height: 4),
                    Text(
                      '${formatter.format(listing.totalValue)} ${'currency'
                          .tr}',
                      style: const TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ],
                ),
              ],
            ),
            const SizedBox(height: 16),
            if (isMyListings)
              SizedBox(
                width: double.infinity,
                child: OutlinedButton.icon(
                  onPressed: () => controller.onCancelListing(listing),
                  icon: const Icon(Icons.cancel_outlined),
                  label: Text('cancel'.tr),
                  style: OutlinedButton.styleFrom(
                    foregroundColor: AppColors.error,
                    side: const BorderSide(color: AppColors.error),
                  ),
                ),
              )
            else
              SizedBox(
                width: double.infinity,
                child: ElevatedButton.icon(
                  onPressed: () => controller.onBuyShares(listing),
                  icon: const Icon(Icons.shopping_cart),
                  label: Text('buy_shares'.tr),
                ),
              ),
          ],
        ),
      ),
    );
  }
}
