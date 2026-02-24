<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Central\PropertyType;
use Illuminate\Http\Request;

class PropertyTypeController extends Controller
{
    public function index()
    {
        $propertyTypes = PropertyType::on('central')
            ->orderBy('sort_order')
            ->orderBy('name_ar')
            ->get();

        return view('admin.property-types.index', compact('propertyTypes'));
    }

    public function create()
    {
        return view('admin.property-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string|max:100',
            'name_en' => 'required|string|max:100',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        PropertyType::create([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()
            ->route('admin.property-types.index')
            ->with('success', 'تم إضافة نوع العقار بنجاح');
    }

    public function edit(PropertyType $propertyType)
    {
        return view('admin.property-types.edit', compact('propertyType'));
    }

    public function update(Request $request, PropertyType $propertyType)
    {
        $request->validate([
            'name_ar' => 'required|string|max:100',
            'name_en' => 'required|string|max:100',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $propertyType->update([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()
            ->route('admin.property-types.index')
            ->with('success', 'تم تحديث نوع العقار بنجاح');
    }

    public function destroy(PropertyType $propertyType)
    {
        // التحقق من عدم وجود عروض تستخدم هذا النوع
        $offersCount = \App\Models\Central\ShareOffer::on('central')
            ->where('property_type', $propertyType->name_ar)
            ->count();

        if ($offersCount > 0) {
            return back()->with('error', 'لا يمكن حذف هذا النوع لأنه مستخدم في ' . $offersCount . ' عرض');
        }

        $propertyType->delete();

        return redirect()
            ->route('admin.property-types.index')
            ->with('success', 'تم حذف نوع العقار بنجاح');
    }
}
