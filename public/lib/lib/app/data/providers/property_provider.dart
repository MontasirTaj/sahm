import 'package:get/get.dart';
import '../models/property_model.dart';

class PropertyProvider extends GetConnect {
  @override
  void onInit() {
    httpClient.baseUrl = 'https://api.sahmi.app'; // Replace with your API
    httpClient.timeout = const Duration(seconds: 30);
  }

  // Get mock data for now - replace with actual API calls
  Future<List<PropertyModel>> getProperties({String? status, String? type}) async {
    // Simulate network delay
    await Future.delayed(const Duration(milliseconds: 800));

    return _getMockProperties();
  }

  Future<List<PropertyModel>> getFeaturedProperties() async {
    await Future.delayed(const Duration(milliseconds: 600));
    return _getMockProperties().take(5).toList();
  }

  Future<List<PropertyModel>> getTrendingProperties() async {
    await Future.delayed(const Duration(milliseconds: 600));
    return _getMockProperties().skip(2).take(5).toList();
  }

  Future<PropertyModel?> getPropertyById(String id) async {
    await Future.delayed(const Duration(milliseconds: 500));
    return _getMockProperties().firstWhereOrNull((p) => p.id == id);
  }

  // Mock data - similar to Ghanem app properties
  List<PropertyModel> _getMockProperties() {
    return [
      PropertyModel(
        id: '1',
        name: 'برج الرياض التجاري',
        description: 'برج تجاري حديث في قلب مدينة الرياض بموقع استراتيجي متميز. يحتوي على 25 طابق مخصص للمكاتب التجارية والإدارية مع مواقف سيارات متعددة الطوابق.',
        propertyType: 'تجاري',
        location: 'شارع الملك فهد، الرياض',
        city: 'الرياض',
        totalValue: 50000000,
        sharePrice: 5000,
        totalShares: 10000,
        availableShares: 3500,
        fundedPercentage: 65,
        expectedAnnualReturn: 12.5,
        investmentPeriodMonths: 60,
        minimumInvestment: 5000,
        images: [
          'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800',
          'https://images.unsplash.com/photo-1582407947304-fd86f028f716?w=800',
        ],
        documents: ['title_deed.pdf', 'engineering_report.pdf'],
        createdAt: DateTime.now().subtract(const Duration(days: 15)),
        fundingDeadline: DateTime.now().add(const Duration(days: 45)),
        status: 'funding',
        additionalInfo: {
          'area': 8500,
          'floors': 25,
          'developer': 'شركة التطوير العقاري الرائدة',
          'amenities': ['مواقف سيارات', 'أمن 24/7', 'مصاعد حديثة', 'نظام إطفاء متطور']
        },
      ),
      PropertyModel(
        id: '2',
        name: 'مجمع الياسمين السكني',
        description: 'مجمع سكني راقي يتكون من 150 وحدة سكنية فاخرة بمساحات متنوعة. يقع في حي راقي ويوفر جميع الخدمات والمرافق الحديثة.',
        propertyType: 'سكني',
        location: 'حي النرجس، الرياض',
        city: 'الرياض',
        totalValue: 75000000,
        sharePrice: 7500,
        totalShares: 10000,
        availableShares: 1200,
        fundedPercentage: 88,
        expectedAnnualReturn: 10.8,
        investmentPeriodMonths: 48,
        minimumInvestment: 7500,
        images: [
          'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=800',
          'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800',
        ],
        documents: ['title_deed.pdf', 'floor_plans.pdf'],
        createdAt: DateTime.now().subtract(const Duration(days: 30)),
        fundingDeadline: DateTime.now().add(const Duration(days: 15)),
        status: 'funding',
        additionalInfo: {
          'area': 12000,
          'units': 150,
          'developer': 'مجموعة الياسمين العقارية',
          'amenities': ['حمام سباحة', 'نادي رياضي', 'حديقة', 'ملاعب أطفال', 'أمن وحراسة']
        },
      ),
      PropertyModel(
        id: '3',
        name: 'مول النخيل التجاري',
        description: 'مركز تجاري حديث على مساحة 20,000 متر مربع يضم محلات تجارية ومطاعم ومقاهي في موقع حيوي.',
        propertyType: 'تجاري',
        location: 'طريق الملك عبدالله، جدة',
        city: 'جدة',
        totalValue: 100000000,
        sharePrice: 10000,
        totalShares: 10000,
        availableShares: 0,
        fundedPercentage: 100,
        expectedAnnualReturn: 14.2,
        investmentPeriodMonths: 60,
        minimumInvestment: 10000,
        images: [
          'https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a?w=800',
          'https://images.unsplash.com/photo-1567449303183-e3bdf3f07295?w=800',
        ],
        documents: ['title_deed.pdf', 'rental_agreements.pdf'],
        createdAt: DateTime.now().subtract(const Duration(days: 90)),
        status: 'funded',
        additionalInfo: {
          'area': 20000,
          'shops': 85,
          'developer': 'النخيل للتطوير التجاري',
          'amenities': ['مواقف واسعة', 'مطاعم وكافيهات', 'منطقة ترفيهية', 'سينما']
        },
      ),
      PropertyModel(
        id: '4',
        name: 'أبراج الواحة السكنية',
        description: 'مشروع أبراج سكنية فاخرة مكون من 3 أبراج بإطلالة بحرية رائعة وتصميم معماري حديث.',
        propertyType: 'سكني',
        location: 'كورنيش جدة',
        city: 'جدة',
        totalValue: 120000000,
        sharePrice: 12000,
        totalShares: 10000,
        availableShares: 4800,
        fundedPercentage: 52,
        expectedAnnualReturn: 11.5,
        investmentPeriodMonths: 60,
        minimumInvestment: 12000,
        images: [
          'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=800',
          'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=800',
        ],
        documents: ['title_deed.pdf', 'architectural_plans.pdf'],
        createdAt: DateTime.now().subtract(const Duration(days: 10)),
        fundingDeadline: DateTime.now().add(const Duration(days: 80)),
        status: 'funding',
        additionalInfo: {
          'area': 45000,
          'towers': 3,
          'units': 280,
          'developer': 'الواحة للاستثمار العقاري',
          'amenities': ['إطلالة بحرية', 'مسابح', 'صالة رياضية', 'شاطئ خاص', 'أمن متطور']
        },
      ),
      PropertyModel(
        id: '5',
        name: 'مجمع المكاتب الإدارية - الخبر',
        description: 'مجمع إداري متكامل يوفر بيئة عمل راقية مع جميع الخدمات المساندة والمرافق الحديثة.',
        propertyType: 'تجاري',
        location: 'الكورنيش، الخبر',
        city: 'الخبر',
        totalValue: 45000000,
        sharePrice: 4500,
        totalShares: 10000,
        availableShares: 2300,
        fundedPercentage: 77,
        expectedAnnualReturn: 13.0,
        investmentPeriodMonths: 48,
        minimumInvestment: 4500,
        images: [
          'https://images.unsplash.com/photo-1497366216548-37526070297c?w=800',
          'https://images.unsplash.com/photo-1497366811353-6870744d04b2?w=800',
        ],
        documents: ['title_deed.pdf', 'business_plan.pdf'],
        createdAt: DateTime.now().subtract(const Duration(days: 25)),
        fundingDeadline: DateTime.now().add(const Duration(days: 35)),
        status: 'funding',
        additionalInfo: {
          'area': 15000,
          'floors': 12,
          'developer': 'الشرقية للتطوير',
          'amenities': ['قاعات اجتماعات', 'كافتيريا', 'مواقف مغطاة', 'إنترنت عالي السرعة']
        },
      ),
      PropertyModel(
        id: '6',
        name: 'منتجع الشاطئ السياحي',
        description: 'منتجع سياحي متكامل على شاطئ البحر الأحمر مع جميع المرافق الترفيهية والفندقية.',
        propertyType: 'سياحي',
        location: 'ينبع',
        city: 'ينبع',
        totalValue: 85000000,
        sharePrice: 8500,
        totalShares: 10000,
        availableShares: 5600,
        fundedPercentage: 44,
        expectedAnnualReturn: 15.0,
        investmentPeriodMonths: 72,
        minimumInvestment: 8500,
        images: [
          'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=800',
          'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=800',
        ],
        documents: ['title_deed.pdf', 'tourism_license.pdf'],
        createdAt: DateTime.now().subtract(const Duration(days: 5)),
        fundingDeadline: DateTime.now().add(const Duration(days: 95)),
        status: 'funding',
        additionalInfo: {
          'area': 35000,
          'rooms': 120,
          'developer': 'السياحة الحديثة القابضة',
          'amenities': ['شاطئ خاص', 'مطاعم فاخرة', 'سبا', 'مرافق رياضية مائية', 'قاعات مؤتمرات']
        },
      ),
      PropertyModel(
        id: '7',
        name: 'مستودعات المنطقة اللوجستية',
        description: 'مجمع مستودعات حديث مجهز بأحدث التقنيات اللوجستية في موقع استراتيجي قرب الميناء.',
        propertyType: 'صناعي',
        location: 'المنطقة الصناعية، الدمام',
        city: 'الدمام',
        totalValue: 35000000,
        sharePrice: 3500,
        totalShares: 10000,
        availableShares: 0,
        fundedPercentage: 100,
        expectedAnnualReturn: 16.5,
        investmentPeriodMonths: 60,
        minimumInvestment: 3500,
        images: [
          'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=800',
          'https://images.unsplash.com/photo-1553413077-190dd305871c?w=800',
        ],
        documents: ['title_deed.pdf', 'industrial_license.pdf'],
        createdAt: DateTime.now().subtract(const Duration(days: 120)),
        status: 'funded',
        additionalInfo: {
          'area': 25000,
          'warehouses': 12,
          'developer': 'اللوجستية المتقدمة',
          'amenities': ['أنظمة أمان متطورة', 'مكاتب إدارية', 'ساحات تحميل', 'موقع استراتيجي']
        },
      ),
      PropertyModel(
        id: '8',
        name: 'قرية الورود السكنية',
        description: 'قرية سكنية متكاملة بتصميم عصري وبيئة هادئة مع جميع الخدمات والمرافق.',
        propertyType: 'سكني',
        location: 'شمال الرياض',
        city: 'الرياض',
        totalValue: 65000000,
        sharePrice: 6500,
        totalShares: 10000,
        availableShares: 2900,
        fundedPercentage: 71,
        expectedAnnualReturn: 9.8,
        investmentPeriodMonths: 48,
        minimumInvestment: 6500,
        images: [
          'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800',
          'https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=800',
        ],
        documents: ['title_deed.pdf', 'master_plan.pdf'],
        createdAt: DateTime.now().subtract(const Duration(days: 20)),
        fundingDeadline: DateTime.now().add(const Duration(days: 40)),
        status: 'funding',
        additionalInfo: {
          'area': 18000,
          'villas': 85,
          'developer': 'الورود للتطوير العقاري',
          'amenities': ['حدائق واسعة', 'مسجد', 'مدارس', 'مراكز تسوق', 'عيادات طبية']
        },
      ),
    ];
  }
}
