import 'package:get/get.dart';
import 'package:flutter/material.dart';
import '../../data/models/property_model.dart';
import '../../data/models/user_model.dart';
import '../../data/repositories/property_repository.dart';
import '../../data/repositories/user_repository.dart';

class HomeController extends GetxController {
  final PropertyRepository propertyRepository;
  final UserRepository userRepository;

  HomeController({
    required this.propertyRepository,
    required this.userRepository,
  });

  final isLoading = false.obs;
  final isRefreshing = false.obs;
  final featuredProperties = <PropertyModel>[].obs;
  final trendingProperties = <PropertyModel>[].obs;
  final allProperties = <PropertyModel>[].obs;
  final filteredProperties = <PropertyModel>[].obs;
  final user = Rxn<UserModel>();
  final searchController = TextEditingController();
  final isSearching = false.obs;

  final totalInvestment = 0.0.obs;
  final totalReturns = 0.0.obs;
  final activeProperties = 0.obs;
  final availableBalance = 0.0.obs;

  @override
  void onInit() {
    super.onInit();
    loadDashboardData();
    searchController.addListener(_onSearchChanged);
  }

  @override
  void onClose() {
    searchController.dispose();
    super.onClose();
  }

  void _onSearchChanged() {
    final query = searchController.text.toLowerCase();
    if (query.isEmpty) {
      isSearching.value = false;
      filteredProperties.clear();
    } else {
      isSearching.value = true;
      filteredProperties.value = allProperties.where((property) {
        return property.name.toLowerCase().contains(query) ||
            property.location.toLowerCase().contains(query) ||
            property.city.toLowerCase().contains(query) ||
            property.propertyType.toLowerCase().contains(query);
      }).toList();
    }
  }

  void clearSearch() {
    searchController.clear();
    isSearching.value = false;
    filteredProperties.clear();
  }

  Future<void> loadDashboardData() async {
    try {
      isLoading.value = true;
      await Future.wait([
        loadUserData(),
        loadProperties(),
      ]);
    } catch (e) {
      Get.snackbar(
        'error'.tr,
        e.toString(),
        snackPosition: SnackPosition.BOTTOM,
      );
    } finally {
      isLoading.value = false;
    }
  }

  Future<void> loadUserData() async {
    try {
      final userData = await userRepository.getCurrentUser();
      if (userData != null) {
        user.value = userData;
        availableBalance.value = userData.balance;
      }

      // Get portfolio summary
      final portfolio = await userRepository.getUserPortfolio(userData?.id ?? 'user_123');
      totalInvestment.value = portfolio.fold(0.0, (sum, item) => sum + item.totalInvested);
      final currentValue = portfolio.fold(0.0, (sum, item) => sum + item.currentValue);
      totalReturns.value = currentValue - totalInvestment.value;
      activeProperties.value = portfolio.length;
    } catch (e) {
      // Error handling
    }
  }

  Future<void> loadProperties() async {
    try {
      final featured = await propertyRepository.getFeaturedProperties();
      featuredProperties.value = featured;

      final trending = await propertyRepository.getTrendingProperties();
      trendingProperties.value = trending;

      // Combine all properties for search
      allProperties.value = [...featured, ...trending];
    } catch (e) {
      // Error loading properties
    }
  }

  @override
  Future<void> refresh() async {
    isRefreshing.value = true;
    await loadDashboardData();
    isRefreshing.value = false;
  }

  List<PropertyModel> _getMockProperties() {
    return [
      PropertyModel(
        id: '1',
        name: 'برج الرياض التجاري',
        description: 'برج تجاري مميز في قلب مدينة الرياض يتكون من 20 طابق',
        propertyType: 'commercial',
        location: 'حي الملقا، الرياض',
        city: 'الرياض',
        totalValue: 5000000,
        sharePrice: 1000,
        totalShares: 5000,
        availableShares: 1200,
        fundedPercentage: 76,
        expectedAnnualReturn: 12.5,
        investmentPeriodMonths: 36,
        minimumInvestment: 5000,
        images: [
          'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab',
          'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00',
        ],
        documents: [],
        createdAt: DateTime.now().subtract(const Duration(days: 15)),
        status: 'funding',
      ),
      PropertyModel(
        id: '2',
        name: 'مجمع الفلل السكنية',
        description: 'مجمع فلل سكنية فاخرة في شمال الرياض',
        propertyType: 'residential',
        location: 'حي النرجس، الرياض',
        city: 'الرياض',
        totalValue: 8500000,
        sharePrice: 850,
        totalShares: 10000,
        availableShares: 3500,
        fundedPercentage: 65,
        expectedAnnualReturn: 10.8,
        investmentPeriodMonths: 48,
        minimumInvestment: 4250,
        images: [
          'https://images.unsplash.com/photo-1564013799919-ab600027ffc6',
          'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9',
        ],
        documents: [],
        createdAt: DateTime.now().subtract(const Duration(days: 10)),
        status: 'funding',
      ),
      PropertyModel(
        id: '3',
        name: 'مركز التسوق الحديث',
        description: 'مركز تسوق عصري يضم أكثر من 100 متجر',
        propertyType: 'commercial',
        location: 'حي الياسمين، جدة',
        city: 'جدة',
        totalValue: 12000000,
        sharePrice: 1200,
        totalShares: 10000,
        availableShares: 2000,
        fundedPercentage: 80,
        expectedAnnualReturn: 14.2,
        investmentPeriodMonths: 60,
        minimumInvestment: 6000,
        images: [
          'https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a',
          'https://images.unsplash.com/photo-1582719508461-905c673771fd',
        ],
        documents: [],
        createdAt: DateTime.now().subtract(const Duration(days: 20)),
        status: 'funding',
      ),
    ];
  }

  void onPropertyTap(PropertyModel property) {
    Get.toNamed('/property-details', arguments: property);
  }

  Future<void> onRefresh() async {
    await loadDashboardData();
  }
}
