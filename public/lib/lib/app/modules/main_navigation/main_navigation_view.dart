import 'package:flutter/material.dart';
import 'package:get/get.dart';
import '../home/home_view.dart';
import '../marketplace/marketplace_view.dart';
import '../portfolio/portfolio_view.dart';
import '../transactions/transactions_view.dart';
import '../profile/profile_view.dart';
import 'main_navigation_controller.dart';

class MainNavigationView extends GetView<MainNavigationController> {
  const MainNavigationView({super.key});

  @override
  Widget build(BuildContext context) {
    return Obx(() => Scaffold(
          body: IndexedStack(
            index: controller.currentIndex.value,
            children: const [
              HomeView(),
              MarketplaceView(),
              PortfolioView(),
              TransactionsView(),
              ProfileView(),
            ],
          ),
          bottomNavigationBar: BottomNavigationBar(
            currentIndex: controller.currentIndex.value,
            onTap: controller.changePage,
            type: BottomNavigationBarType.fixed,
            selectedFontSize: 12,
            unselectedFontSize: 12,
            items: [
              BottomNavigationBarItem(
                icon: const Icon(Icons.home_outlined),
                activeIcon: const Icon(Icons.home),
                label: 'nav_home'.tr,
              ),
              BottomNavigationBarItem(
                icon: const Icon(Icons.store_outlined),
                activeIcon: const Icon(Icons.store),
                label: 'nav_marketplace'.tr,
              ),
              BottomNavigationBarItem(
                icon: const Icon(Icons.pie_chart_outline),
                activeIcon: const Icon(Icons.pie_chart),
                label: 'nav_portfolio'.tr,
              ),
              BottomNavigationBarItem(
                icon: const Icon(Icons.receipt_long_outlined),
                activeIcon: const Icon(Icons.receipt_long),
                label: 'nav_transactions'.tr,
              ),
              BottomNavigationBarItem(
                icon: const Icon(Icons.person_outline),
                activeIcon: const Icon(Icons.person),
                label: 'nav_profile'.tr,
              ),
            ],
          ),
        ));
  }
}
