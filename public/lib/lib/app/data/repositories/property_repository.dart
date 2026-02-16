import '../models/property_model.dart';
import '../providers/property_provider.dart';

class PropertyRepository {
  final PropertyProvider _provider;

  PropertyRepository(this._provider);

  Future<List<PropertyModel>> getProperties({String? status, String? type}) async {
    try {
      return await _provider.getProperties(status: status, type: type);
    } catch (e) {
      throw Exception('Failed to load properties: $e');
    }
  }

  Future<List<PropertyModel>> getFeaturedProperties() async {
    try {
      return await _provider.getFeaturedProperties();
    } catch (e) {
      throw Exception('Failed to load featured properties: $e');
    }
  }

  Future<List<PropertyModel>> getTrendingProperties() async {
    try {
      return await _provider.getTrendingProperties();
    } catch (e) {
      throw Exception('Failed to load trending properties: $e');
    }
  }

  Future<PropertyModel?> getPropertyById(String id) async {
    try {
      return await _provider.getPropertyById(id);
    } catch (e) {
      throw Exception('Failed to load property details: $e');
    }
  }
}
