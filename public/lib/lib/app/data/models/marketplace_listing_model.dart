class MarketplaceListingModel {
  final String id;
  final String sellerId;
  final String sellerName;
  final String propertyId;
  final String propertyName;
  final String propertyImage;
  final int sharesAvailable;
  final double pricePerShare;
  final double totalValue;
  final DateTime createdAt;
  final String status; // active, sold, cancelled
  final DateTime? expiresAt;

  MarketplaceListingModel({
    required this.id,
    required this.sellerId,
    required this.sellerName,
    required this.propertyId,
    required this.propertyName,
    required this.propertyImage,
    required this.sharesAvailable,
    required this.pricePerShare,
    required this.totalValue,
    required this.createdAt,
    required this.status,
    this.expiresAt,
  });

  factory MarketplaceListingModel.fromJson(Map<String, dynamic> json) {
    return MarketplaceListingModel(
      id: json['id'] ?? '',
      sellerId: json['sellerId'] ?? '',
      sellerName: json['sellerName'] ?? '',
      propertyId: json['propertyId'] ?? '',
      propertyName: json['propertyName'] ?? '',
      propertyImage: json['propertyImage'] ?? '',
      sharesAvailable: json['sharesAvailable'] ?? 0,
      pricePerShare: (json['pricePerShare'] ?? 0).toDouble(),
      totalValue: (json['totalValue'] ?? 0).toDouble(),
      createdAt: json['createdAt'] != null
          ? DateTime.parse(json['createdAt'])
          : DateTime.now(),
      status: json['status'] ?? 'active',
      expiresAt: json['expiresAt'] != null
          ? DateTime.parse(json['expiresAt'])
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'sellerId': sellerId,
      'sellerName': sellerName,
      'propertyId': propertyId,
      'propertyName': propertyName,
      'propertyImage': propertyImage,
      'sharesAvailable': sharesAvailable,
      'pricePerShare': pricePerShare,
      'totalValue': totalValue,
      'createdAt': createdAt.toIso8601String(),
      'status': status,
      'expiresAt': expiresAt?.toIso8601String(),
    };
  }

  bool get isActive => status == 'active';
  bool get isSold => status == 'sold';
  bool get isCancelled => status == 'cancelled';
}
