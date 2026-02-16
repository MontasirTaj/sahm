import 'package:get/get.dart';
import '../models/marketplace_listing_model.dart';

class MarketplaceProvider extends GetConnect {
  @override
  void onInit() {
    httpClient.baseUrl = 'https://api.sahmi.app';
    httpClient.timeout = const Duration(seconds: 30);
  }

  Future<List<MarketplaceListingModel>> getListings({String? propertyId}) async {
    await Future.delayed(const Duration(milliseconds: 700));
    return _getMockListings();
  }

  Future<List<MarketplaceListingModel>> getUserListings(String userId) async {
    await Future.delayed(const Duration(milliseconds: 600));
    return _getMockListings().where((l) => l.sellerId == userId).toList();
  }

  Future<bool> createListing(MarketplaceListingModel listing) async {
    await Future.delayed(const Duration(milliseconds: 800));
    return true;
  }

  Future<bool> cancelListing(String listingId) async {
    await Future.delayed(const Duration(milliseconds: 600));
    return true;
  }

  Future<bool> purchaseListing(String listingId, String buyerId) async {
    await Future.delayed(const Duration(milliseconds: 1000));
    return true;
  }

  // Mock data
  List<MarketplaceListingModel> _getMockListings() {
    return [
      MarketplaceListingModel(
        id: 'ml1',
        sellerId: 'seller_1',
        sellerName: 'أحمد السالم',
        propertyId: '1',
        propertyName: 'برج الرياض التجاري',
        propertyImage: 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800',
        sharesAvailable: 20,
        pricePerShare: 5500,
        totalValue: 110000,
        status: 'active',
        createdAt: DateTime.now().subtract(const Duration(days: 2)),
      ),
      MarketplaceListingModel(
        id: 'ml2',
        sellerId: 'seller_2',
        sellerName: 'محمد الغامدي',
        propertyId: '3',
        propertyName: 'مول النخيل التجاري',
        propertyImage: 'https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a?w=800',
        sharesAvailable: 15,
        pricePerShare: 11500,
        totalValue: 172500,
        status: 'active',
        createdAt: DateTime.now().subtract(const Duration(days: 1)),
      ),
      MarketplaceListingModel(
        id: 'ml3',
        sellerId: 'seller_3',
        sellerName: 'خالد العتيبي',
        propertyId: '2',
        propertyName: 'مجمع الياسمين السكني',
        propertyImage: 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=800',
        sharesAvailable: 30,
        pricePerShare: 7800,
        totalValue: 234000,
        status: 'active',
        createdAt: DateTime.now().subtract(const Duration(hours: 12)),
      ),
      MarketplaceListingModel(
        id: 'ml4',
        sellerId: 'seller_1',
        sellerName: 'أحمد السالم',
        propertyId: '7',
        propertyName: 'مستودعات المنطقة اللوجستية',
        propertyImage: 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=800',
        sharesAvailable: 40,
        pricePerShare: 3800,
        totalValue: 152000,
        status: 'active',
        createdAt: DateTime.now().subtract(const Duration(days: 3)),
      ),
      MarketplaceListingModel(
        id: 'ml5',
        sellerId: 'seller_4',
        sellerName: 'فهد القحطاني',
        propertyId: '3',
        propertyName: 'مول النخيل التجاري',
        propertyImage: 'https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a?w=800',
        sharesAvailable: 10,
        pricePerShare: 11300,
        totalValue: 113000,
        status: 'active',
        createdAt: DateTime.now().subtract(const Duration(hours: 6)),
      ),
    ];
  }
}
