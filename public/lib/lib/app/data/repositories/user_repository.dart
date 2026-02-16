import '../models/user_model.dart';
import '../models/portfolio_item_model.dart';
import '../models/transaction_model.dart';
import '../providers/user_provider.dart';

class UserRepository {
  final UserProvider _provider;

  UserRepository(this._provider);

  Future<UserModel?> getCurrentUser() async {
    try {
      return await _provider.getCurrentUser();
    } catch (e) {
      throw Exception('Failed to load user: $e');
    }
  }

  Future<List<PortfolioItemModel>> getUserPortfolio(String userId) async {
    try {
      return await _provider.getUserPortfolio(userId);
    } catch (e) {
      throw Exception('Failed to load portfolio: $e');
    }
  }

  Future<List<TransactionModel>> getUserTransactions(String userId) async {
    try {
      return await _provider.getUserTransactions(userId);
    } catch (e) {
      throw Exception('Failed to load transactions: $e');
    }
  }

  Future<bool> updateProfile(UserModel user) async {
    try {
      return await _provider.updateProfile(user);
    } catch (e) {
      throw Exception('Failed to update profile: $e');
    }
  }

  Future<bool> addFunds(double amount) async {
    try {
      return await _provider.addFunds(amount);
    } catch (e) {
      throw Exception('Failed to add funds: $e');
    }
  }

  Future<bool> withdrawFunds(double amount) async {
    try {
      return await _provider.withdrawFunds(amount);
    } catch (e) {
      throw Exception('Failed to withdraw funds: $e');
    }
  }
}
