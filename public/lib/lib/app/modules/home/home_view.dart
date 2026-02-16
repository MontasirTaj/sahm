import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:intl/intl.dart';
import 'package:animate_do/animate_do.dart';
import '../../../core/theme/app_colors.dart';
import '../../widgets/property_card.dart';
import '../../widgets/shimmer_loading.dart';
import '../../widgets/empty_state.dart';
import 'home_controller.dart';
import 'widgets/stat_card.dart';

class HomeView extends GetView<HomeController> {
  const HomeView({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: Text('app_name'.tr),
        actions: [
          IconButton(
            icon: const Icon(Icons.notifications_outlined),
            onPressed: () {
              Get.snackbar(
                'قريباً',
                'سيتم إضافة الإشعارات قريباً',
                snackPosition: SnackPosition.TOP,
                duration: const Duration(seconds: 2),
              );
            },
          ),
        ],
      ),
      body: Obx(() {
        if (controller.isLoading.value && controller.featuredProperties.isEmpty) {
          return _buildShimmerLoading();
        }

        return RefreshIndicator(
          onRefresh: controller.refresh,
          child: SingleChildScrollView(
            physics: const AlwaysScrollableScrollPhysics(),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                FadeInDown(
                  duration: const Duration(milliseconds: 500),
                  child: _buildHeader(),
                ),
                const SizedBox(height: 20),
                FadeInUp(
                  duration: const Duration(milliseconds: 600),
                  child: _buildSearchBar(),
                ),
                const SizedBox(height: 20),
                FadeInUp(
                  duration: const Duration(milliseconds: 700),
                  child: _buildStatsCards(),
                ),
                const SizedBox(height: 24),
                Obx(() => controller.isSearching.value
                    ? _buildSearchResults()
                    : Column(
                        children: [
                          _buildFeaturedProperties(),
                          const SizedBox(height: 24),
                          _buildTrendingProperties(),
                        ],
                      )),
                const SizedBox(height: 20),
              ],
            ),
          ),
        );
      }),
    );
  }

  Widget _buildShimmerLoading() {
    return SingleChildScrollView(
      child: Column(
        children: [
          Container(
            height: 200,
            color: AppColors.primary.withOpacity(0.1),
          ),
          const SizedBox(height: 20),
          Row(
            children: [
              Expanded(child: ShimmerLoading(width: 100, height: 80)),
              const SizedBox(width: 8),
              Expanded(child: ShimmerLoading(width: 100, height: 80)),
              const SizedBox(width: 8),
              Expanded(child: ShimmerLoading(width: 100, height: 80)),
            ].map((w) => Padding(padding: EdgeInsets.symmetric(horizontal: 4), child: w)).toList(),
          ),
          const SizedBox(height: 20),
          ...List.generate(3, (index) => const PropertyCardShimmer()),
        ],
      ),
    );
  }

  Widget _buildSearchBar() {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      child: TextField(
        controller: controller.searchController,
        decoration: InputDecoration(
          hintText: 'ابحث عن عقار...',
          prefixIcon: const Icon(Icons.search, color: AppColors.primary),
          suffixIcon: Obx(() => controller.isSearching.value
              ? IconButton(
                  icon: const Icon(Icons.clear),
                  onPressed: controller.clearSearch,
                )
              : const SizedBox.shrink()),
          filled: true,
          fillColor: Colors.white,
          border: OutlineInputBorder(
            borderRadius: BorderRadius.circular(12),
            borderSide: BorderSide(color: AppColors.border),
          ),
          enabledBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(12),
            borderSide: BorderSide(color: AppColors.border),
          ),
          focusedBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(12),
            borderSide: BorderSide(color: AppColors.primary, width: 2),
          ),
        ),
      ),
    );
  }

  Widget _buildSearchResults() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 16),
          child: Text(
            'نتائج البحث (${controller.filteredProperties.length})',
            style: const TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.bold,
            ),
          ),
        ),
        const SizedBox(height: 12),
        Obx(() {
          if (controller.filteredProperties.isEmpty) {
            return EmptyState(
              icon: Icons.search_off,
              title: 'لا توجد نتائج',
              message: 'لم نجد عقارات تطابق بحثك. حاول استخدام كلمات مختلفة.',
            );
          }
          return Column(
            children: controller.filteredProperties.map((property) {
              return FadeInUp(
                duration: const Duration(milliseconds: 400),
                child: PropertyCard(
                  property: property,
                  onTap: () => controller.onPropertyTap(property),
                ),
              );
            }).toList(),
          );
        }),
      ],
    );
  }

  Widget _buildHeader() {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        gradient: AppColors.primaryGradient,
        borderRadius: const BorderRadius.only(
          bottomLeft: Radius.circular(30),
          bottomRight: Radius.circular(30),
        ),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            'welcome'.tr,
            style: const TextStyle(
              color: AppColors.textWhite,
              fontSize: 16,
            ),
          ),
          const SizedBox(height: 8),
          Obx(() => Text(
            controller.user.value?.fullName ?? 'المستخدم',
            style: const TextStyle(
              color: AppColors.textWhite,
              fontSize: 24,
              fontWeight: FontWeight.bold,
            ),
          )),
          const SizedBox(height: 20),
          Row(
            children: [
              Expanded(
                child: _buildHeaderStat(
                  'total_investment'.tr,
                  controller.totalInvestment.value,
                ),
              ),
              const SizedBox(width: 16),
              Expanded(
                child: _buildHeaderStat(
                  'total_returns'.tr,
                  controller.totalReturns.value,
                  isProfit: controller.totalReturns.value >= 0,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildHeaderStat(String label, double value, {bool isProfit = false}) {
    final formatter = NumberFormat('#,##0', 'ar');
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white.withOpacity(0.2),
        borderRadius: BorderRadius.circular(12),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            label,
            style: const TextStyle(
              color: AppColors.textWhite,
              fontSize: 12,
            ),
          ),
          const SizedBox(height: 4),
          Row(
            children: [
              Flexible(
                child: Text(
                  '${formatter.format(value)} ر.س',
                  style: const TextStyle(
                    color: AppColors.textWhite,
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                  ),
                  overflow: TextOverflow.ellipsis,
                ),
              ),
              if (isProfit && value > 0) ...[
                const SizedBox(width: 4),
                const Icon(
                  Icons.trending_up,
                  color: AppColors.textWhite,
                  size: 18,
                ),
              ],
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildStatsCards() {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 20),
      child: Row(
        children: [
          Expanded(
            child: StatCard(
              icon: Icons.account_balance_wallet,
              title: 'available_balance'.tr,
              value: controller.availableBalance.value,
              color: AppColors.info,
            ),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: StatCard(
              icon: Icons.business,
              title: 'active_properties'.tr,
              value: controller.activeProperties.value.toDouble(),
              isCount: true,
              color: AppColors.secondary,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildFeaturedProperties() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 20),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                'featured_properties'.tr,
                style: const TextStyle(
                  fontSize: 20,
                  fontWeight: FontWeight.bold,
                ),
              ),
              TextButton(
                onPressed: () => Get.toNamed('/marketplace'),
                child: Text('view_all'.tr),
              ),
            ],
          ),
        ),
        const SizedBox(height: 12),
        Obx(() {
          if (controller.featuredProperties.isEmpty) {
            return const SizedBox.shrink();
          }
          return SizedBox(
            height: 380,
            child: ListView.builder(
              padding: const EdgeInsets.symmetric(horizontal: 20),
              scrollDirection: Axis.horizontal,
              itemCount: controller.featuredProperties.length,
              itemBuilder: (context, index) {
                final property = controller.featuredProperties[index];
                return Container(
                  width: 300,
                  margin: EdgeInsets.only(
                    left: index < controller.featuredProperties.length - 1 ? 16 : 0,
                  ),
                  child: PropertyCard(property: property),
                );
              },
            ),
          );
        }),
      ],
    );
  }

  Widget _buildTrendingProperties() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 20),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                'trending_properties'.tr,
                style: const TextStyle(
                  fontSize: 20,
                  fontWeight: FontWeight.bold,
                ),
              ),
              TextButton(
                onPressed: () => Get.toNamed('/marketplace'),
                child: Text('view_all'.tr),
              ),
            ],
          ),
        ),
        const SizedBox(height: 12),
        Obx(() {
          if (controller.trendingProperties.isEmpty) {
            return const Padding(
              padding: EdgeInsets.all(40),
              child: Center(
                child: Text('لا توجد عقارات متاحة'),
              ),
            );
          }
          return ListView.separated(
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            padding: const EdgeInsets.symmetric(horizontal: 20),
            itemCount: controller.trendingProperties.length.clamp(0, 3),
            separatorBuilder: (context, index) => const SizedBox(height: 16),
            itemBuilder: (context, index) {
              final property = controller.trendingProperties[index];
              return PropertyCard(property: property);
            },
          );
        }),
      ],
    );
  }
}
