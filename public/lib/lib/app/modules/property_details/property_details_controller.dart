import 'package:flutter/material.dart';
import 'package:get/get.dart';
import '../../data/models/property_model.dart';

class PropertyDetailsController extends GetxController {
  final property = Rxn<PropertyModel>();
  final selectedImageIndex = 0.obs;
  final sharesController = TextEditingController();
  final numberOfShares = 0.obs;
  final totalAmount = 0.0.obs;

  @override
  void onInit() {
    super.onInit();
    property.value = Get.arguments as PropertyModel?;
    sharesController.addListener(_calculateTotal);
  }

  @override
  void onClose() {
    sharesController.dispose();
    super.onClose();
  }

  void _calculateTotal() {
    final shares = int.tryParse(sharesController.text) ?? 0;
    numberOfShares.value = shares;
    totalAmount.value = shares * (property.value?.sharePrice ?? 0);
  }

  void changeImage(int index) {
    selectedImageIndex.value = index;
  }

  void buyShares() {
    if (property.value == null) return;

    final shares = int.tryParse(sharesController.text) ?? 0;

    if (shares <= 0) {
      Get.snackbar('error'.tr, 'invalid_amount'.tr);
      return;
    }

    if (shares > property.value!.availableShares) {
      Get.snackbar('error'.tr, 'insufficient_shares'.tr);
      return;
    }

    Get.dialog(
      AlertDialog(
        title: Text('confirm_purchase'.tr),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('${'number_of_shares'.tr}: $shares'),
            Text('${'total_amount'.tr}: ${totalAmount.value.toStringAsFixed(2)} ${'currency'.tr}'),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () => Get.back(),
            child: Text('cancel'.tr),
          ),
          ElevatedButton(
            onPressed: () {
              Get.back();
              Get.back();
              Get.snackbar(
                'success'.tr,
                'purchase_successful'.tr,
                snackPosition: SnackPosition.BOTTOM,
              );
            },
            child: Text('confirm'.tr),
          ),
        ],
      ),
    );
  }
}
