import 'package:get/get.dart';
import '../models/user_model.dart';
import '../models/portfolio_item_model.dart';
import '../models/transaction_model.dart';

class UserProvider extends GetConnect {
  @override
  void onInit() {
    httpClient.baseUrl = 'https://api.sahmi.app';
    httpClient.timeout = const Duration(seconds: 30);
  }

  Future<UserModel?> getCurrentUser() async {
    await Future.delayed(const Duration(milliseconds: 500));
    return _getMockUser();
  }

  Future<List<PortfolioItemModel>> getUserPortfolio(String userId) async {
    await Future.delayed(const Duration(milliseconds: 700));
    return _getMockPortfolio();
  }

  Future<List<TransactionModel>> getUserTransactions(String userId) async {
    await Future.delayed(const Duration(milliseconds: 700));
    return _getMockTransactions();
  }

  Future<bool> updateProfile(UserModel user) async {
    await Future.delayed(const Duration(milliseconds: 800));
    return true;
  }

  Future<bool> addFunds(double amount) async {
    await Future.delayed(const Duration(milliseconds: 1000));
    return true;
  }

  Future<bool> withdrawFunds(double amount) async {
    await Future.delayed(const Duration(milliseconds: 1000));
    return true;
  }

  // Mock data
  UserModel _getMockUser() {
    return UserModel(
      id: 'user_123',
      fullName: 'عبدالله محمد السعيد',
      email: 'abdullah@example.com',
      phone: '+966501234567',
      isVerified: true,
      balance: 45750.00,
      createdAt: DateTime.now().subtract(const Duration(days: 180)),
    );
  }

  List<PortfolioItemModel> _getMockPortfolio() {
    return [
      PortfolioItemModel(
        id: 'p1',
        propertyId: '1',
        propertyName: 'برج الرياض التجاري',
        propertyImage: 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800',
        propertyType: 'تجاري',
        sharesOwned: 50,
        purchasePrice: 5000,
        currentPrice: 5450,
        totalInvested: 250000,
        currentValue: 272500,
        profitLoss: 22500,
        profitPercentage: 9.0,
        investmentDate: DateTime.now().subtract(const Duration(days: 120)),
        totalDividends: 12500,
      ),
      PortfolioItemModel(
        id: 'p2',
        propertyId: '3',
        propertyName: 'مول النخيل التجاري',
        propertyImage: 'https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a?w=800',
        propertyType: 'تجاري',
        sharesOwned: 30,
        purchasePrice: 10000,
        currentPrice: 11200,
        totalInvested: 300000,
        currentValue: 336000,
        profitLoss: 36000,
        profitPercentage: 12.0,
        investmentDate: DateTime.now().subtract(const Duration(days: 150)),
        totalDividends: 25500,
      ),
      PortfolioItemModel(
        id: 'p3',
        propertyId: '2',
        propertyName: 'مجمع الياسمين السكني',
        propertyImage: 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=800',
        propertyType: 'سكني',
        sharesOwned: 25,
        purchasePrice: 7500,
        currentPrice: 7800,
        totalInvested: 187500,
        currentValue: 195000,
        profitLoss: 7500,
        profitPercentage: 4.0,
        investmentDate: DateTime.now().subtract(const Duration(days: 90)),
        totalDividends: 8750,
      ),
    ];
  }

  List<TransactionModel> _getMockTransactions() {
    return [
      TransactionModel(
        id: 't1',
        userId: 'user_123',
        propertyId: '1',
        propertyName: 'برج الرياض التجاري',
        type: 'purchase',
        shares: 25,
        pricePerShare: 5000,
        amount: 125000,
        fee: 625,
        status: 'completed',
        createdAt: DateTime.now().subtract(const Duration(days: 5)),
        completedAt: DateTime.now().subtract(const Duration(days: 5)),
      ),
      TransactionModel(
        id: 't2',
        userId: 'user_123',
        propertyId: '3',
        propertyName: 'مول النخيل التجاري',
        type: 'dividend',
        shares: 30,
        pricePerShare: 10000,
        amount: 4250,
        status: 'completed',
        createdAt: DateTime.now().subtract(const Duration(days: 10)),
        completedAt: DateTime.now().subtract(const Duration(days: 10)),
      ),
      TransactionModel(
        id: 't3',
        userId: 'user_123',
        propertyId: '2',
        propertyName: 'مجمع الياسمين السكني',
        type: 'purchase',
        shares: 15,
        pricePerShare: 7500,
        amount: 112500,
        fee: 562.50,
        status: 'completed',
        createdAt: DateTime.now().subtract(const Duration(days: 15)),
        completedAt: DateTime.now().subtract(const Duration(days: 15)),
      ),
      TransactionModel(
        id: 't4',
        userId: 'user_123',
        propertyId: '1',
        propertyName: 'برج الرياض التجاري',
        type: 'dividend',
        shares: 50,
        pricePerShare: 5000,
        amount: 3125,
        status: 'completed',
        createdAt: DateTime.now().subtract(const Duration(days: 20)),
        completedAt: DateTime.now().subtract(const Duration(days: 20)),
      ),
      TransactionModel(
        id: 't5',
        userId: 'user_123',
        propertyId: '0',
        propertyName: 'إضافة رصيد',
        type: 'deposit',
        shares: 0,
        amount: 50000,
        status: 'completed',
        createdAt: DateTime.now().subtract(const Duration(days: 25)),
        completedAt: DateTime.now().subtract(const Duration(days: 25)),
      ),
    ];
  }
}
