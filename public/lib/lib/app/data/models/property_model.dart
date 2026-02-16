class PropertyModel {
  final String id;
  final String name;
  final String description;
  final String propertyType;
  final String location;
  final String city;
  final double totalValue;
  final double sharePrice;
  final int totalShares;
  final int availableShares;
  final double fundedPercentage;
  final double expectedAnnualReturn;
  final int investmentPeriodMonths;
  final double minimumInvestment;
  final List<String> images;
  final List<String> documents;
  final DateTime createdAt;
  final DateTime? fundingDeadline;
  final String status;
  final Map<String, dynamic>? additionalInfo;

  PropertyModel({
    required this.id,
    required this.name,
    required this.description,
    required this.propertyType,
    required this.location,
    required this.city,
    required this.totalValue,
    required this.sharePrice,
    required this.totalShares,
    required this.availableShares,
    required this.fundedPercentage,
    required this.expectedAnnualReturn,
    required this.investmentPeriodMonths,
    required this.minimumInvestment,
    required this.images,
    required this.documents,
    required this.createdAt,
    this.fundingDeadline,
    required this.status,
    this.additionalInfo,
  });

  factory PropertyModel.fromJson(Map<String, dynamic> json) {
    return PropertyModel(
      id: json['id'] ?? '',
      name: json['name'] ?? '',
      description: json['description'] ?? '',
      propertyType: json['propertyType'] ?? '',
      location: json['location'] ?? '',
      city: json['city'] ?? '',
      totalValue: (json['totalValue'] ?? 0).toDouble(),
      sharePrice: (json['sharePrice'] ?? 0).toDouble(),
      totalShares: json['totalShares'] ?? 0,
      availableShares: json['availableShares'] ?? 0,
      fundedPercentage: (json['fundedPercentage'] ?? 0).toDouble(),
      expectedAnnualReturn: (json['expectedAnnualReturn'] ?? 0).toDouble(),
      investmentPeriodMonths: json['investmentPeriodMonths'] ?? 0,
      minimumInvestment: (json['minimumInvestment'] ?? 0).toDouble(),
      images: List<String>.from(json['images'] ?? []),
      documents: List<String>.from(json['documents'] ?? []),
      createdAt: json['createdAt'] != null
          ? DateTime.parse(json['createdAt'])
          : DateTime.now(),
      fundingDeadline: json['fundingDeadline'] != null
          ? DateTime.parse(json['fundingDeadline'])
          : null,
      status: json['status'] ?? 'funding',
      additionalInfo: json['additionalInfo'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'description': description,
      'propertyType': propertyType,
      'location': location,
      'city': city,
      'totalValue': totalValue,
      'sharePrice': sharePrice,
      'totalShares': totalShares,
      'availableShares': availableShares,
      'fundedPercentage': fundedPercentage,
      'expectedAnnualReturn': expectedAnnualReturn,
      'investmentPeriodMonths': investmentPeriodMonths,
      'minimumInvestment': minimumInvestment,
      'images': images,
      'documents': documents,
      'createdAt': createdAt.toIso8601String(),
      'fundingDeadline': fundingDeadline?.toIso8601String(),
      'status': status,
      'additionalInfo': additionalInfo,
    };
  }

  int get soldShares => totalShares - availableShares;

  bool get isFullyFunded => availableShares == 0;

  bool get isFunding => status == 'funding' && availableShares > 0;
}
