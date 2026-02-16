import '../models/marketplace_listing_model.dart';
import '../providers/marketplace_provider.dart';

class MarketplaceRepository {
  final MarketplaceProvider _provider;

  MarketplaceRepository(this._provider);

  Future<List<MarketplaceListingModel>> getListings({String? propertyId}) async {
    try {
      return await _provider.getListings(propertyId: propertyId);
    } catch (e) {
      throw Exception('Failed to load marketplace listings: $e');
    }
  }

  Future<List<MarketplaceListingModel>> getUserListings(String userId) async {
    try {
      return await _provider.getUserListings(userId);
    } catch (e) {
      throw Exception('Failed to load user listings: $e');
    }
  }

  Future<bool> createListing(MarketplaceListingModel listing) async {
    try {
      return await _provider.createListing(listing);
    } catch (e) {
      throw Exception('Failed to create listing: $e');
    }
  }

  Future<bool> cancelListing(String listingId) async {
    try {
      return await _provider.cancelListing(listingId);
    } catch (e) {
      throw Exception('Failed to cancel listing: $e');
    }
  }

  Future<bool> purchaseListing(String listingId, String buyerId) async {
    try {
      return await _provider.purchaseListing(listingId, buyerId);
    } catch (e) {
      throw Exception('Failed to purchase listing: $e');
    }
  }
}
