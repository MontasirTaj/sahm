# 🏗️ Sahmi Web Frontend Specification

## 📋 Project Overview

**Project Name:** Sahmi (سهمي) - Fractional Real Estate Investment Platform  
**Type:** Web Application (Frontend)  
**Target:** Saudi Arabian Market  
**Languages:** Arabic (Primary) + English  
**Purpose:** Enable fractional ownership of real estate properties through share-based investment

---

## 🎨 Design System

### **Brand Identity**

**App Name:** سهمي - Sahmi  
**Tagline (AR):** استثمر في العقارات بسهولة  
**Tagline (EN):** Invest in Real Estate Easily

### **Color Palette**

```css
/* Primary Colors */
--primary: #1A5F3F;           /* Deep Green - Main brand color */
--primary-light: #2D7A56;     /* Lighter green for hover states */
--primary-dark: #0F4029;      /* Darker green for active states */

/* Secondary Colors */
--secondary: #D4AF37;         /* Gold - Accent color */
--secondary-light: #E5C968;   /* Light gold */
--secondary-dark: #B8961F;    /* Dark gold */

/* Background Colors */
--background: #F5F7FA;        /* Light gray background */
--surface: #FFFFFF;           /* White cards/surfaces */
--border: #E0E4E8;            /* Light border color */

/* Text Colors */
--text-primary: #1E293B;      /* Dark gray for main text */
--text-secondary: #64748B;    /* Medium gray for secondary text */
--text-white: #FFFFFF;        /* White text on dark backgrounds */

/* Status Colors */
--success: #10B981;           /* Green for success/profit */
--warning: #F59E0B;           /* Orange for warnings */
--error: #EF4444;             /* Red for errors/loss */
--info: #3B82F6;              /* Blue for information */
```

### **Gradients**

```css
/* Primary Gradient - Used in headers, CTA buttons */
background: linear-gradient(135deg, #1A5F3F 0%, #2D7A56 100%);

/* Secondary Gradient - Used for accents */
background: linear-gradient(135deg, #D4AF37 0%, #E5C968 100%);

/* Subtle Background Gradient */
background: linear-gradient(180deg, #F5F7FA 0%, #FFFFFF 100%);
```

### **Typography**

**Font Family:**
```css
/* Primary Font (Arabic Support) */
font-family: 'Cairo', 'Tajawal', sans-serif;

/* Fallback for English */
font-family: 'Inter', 'Roboto', -apple-system, BlinkMacSystemFont, sans-serif;
```

**Font Weights:**
- Regular: 400
- Medium: 500
- Semi-bold: 600
- Bold: 700

**Font Sizes:**
```css
--font-xs: 12px;      /* Small labels, badges */
--font-sm: 14px;      /* Secondary text, descriptions */
--font-base: 16px;    /* Body text */
--font-lg: 18px;      /* Subheadings */
--font-xl: 20px;      /* Section titles */
--font-2xl: 24px;     /* Page titles */
--font-3xl: 32px;     /* Hero titles */
--font-4xl: 40px;     /* Large displays */
```

**Line Heights:**
- Tight: 1.2 (headings)
- Normal: 1.5 (body text)
- Relaxed: 1.75 (long-form content)

### **Spacing System**

```css
--space-1: 4px;
--space-2: 8px;
--space-3: 12px;
--space-4: 16px;
--space-5: 20px;
--space-6: 24px;
--space-8: 32px;
--space-10: 40px;
--space-12: 48px;
--space-16: 64px;
--space-20: 80px;
```

### **Border Radius**

```css
--radius-sm: 8px;     /* Small components */
--radius-md: 12px;    /* Cards, buttons */
--radius-lg: 16px;    /* Large cards */
--radius-xl: 20px;    /* Modals, dialogs */
--radius-full: 9999px; /* Circular elements */
```

### **Shadows**

```css
/* Card Shadow */
box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);

/* Elevated Card */
box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);

/* Modal/Dialog */
box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);

/* Button Hover */
box-shadow: 0 4px 12px rgba(26, 95, 63, 0.3);
```

---

## 🏛️ Application Structure

### **Page Hierarchy**

```
Root
├── Onboarding (First time only)
├── Authentication
│   ├── Login
│   └── Register
└── Main Application
    ├── Home (Dashboard)
    ├── Marketplace
    ├── Portfolio
    ├── Transactions
    └── Profile
```

### **Navigation**

**Bottom Navigation Bar** (Mobile) / **Sidebar** (Desktop)
- Home (Icon: house/home)
- Marketplace (Icon: store/shopping)
- Portfolio (Icon: pie-chart/briefcase)
- Transactions (Icon: receipt/list)
- Profile (Icon: user/person)

**Active State:**
- Icon color: Primary green
- Label color: Primary green
- Bottom border (mobile) or left border (desktop): 3px solid primary

---

## 📱 Screens & Features Detailed Specification

### **1. Onboarding Screen (First Launch Only)**

**Layout:**
- Full-screen slider with 4 slides
- Progress dots at bottom
- Skip button (top-right)
- Next/Back arrows or swipe gesture
- "Get Started" button on last slide

**Slide 1:**
- **Icon:** House/Building (Green, 100px)
- **Title (AR):** استثمر في العقارات بسهولة
- **Title (EN):** Invest in Real Estate Easily
- **Description (AR):** ابدأ الاستثمار في العقارات من خلال شراء أسهم جزئية بأقل المبالغ
- **Description (EN):** Start investing in real estate by purchasing fractional shares with minimal amounts

**Slide 2:**
- **Icon:** Pie Chart (Gold, 100px)
- **Title (AR):** تنوع محفظتك الاستثمارية
- **Title (EN):** Diversify Your Investment Portfolio
- **Description (AR):** استثمر في عقارات متنوعة (سكنية، تجارية، صناعية) لتقليل المخاطر
- **Description (EN):** Invest in diverse properties (residential, commercial, industrial) to reduce risks

**Slide 3:**
- **Icon:** Trending Up (Success Green, 100px)
- **Title (AR):** عوائد دورية مضمونة
- **Title (EN):** Guaranteed Periodic Returns
- **Description (AR):** احصل على عوائد دورية من الإيجارات وزيادة قيمة العقار
- **Description (EN):** Receive periodic returns from rent and property value appreciation

**Slide 4:**
- **Icon:** Exchange/Swap (Primary Green, 100px)
- **Title (AR):** تداول الأسهم بسهولة
- **Title (EN):** Trade Shares Easily
- **Description (AR):** اشترِ وبع أسهمك في السوق الثانوي بكل مرونة وشفافية
- **Description (EN):** Buy and sell your shares in the secondary market with flexibility and transparency

**Technical Notes:**
- Store "onboarding_completed" flag in localStorage
- Only show on first visit
- Smooth slide transitions (300ms)

---

### **2. Login Screen**

**Layout:**
- Centered card (max-width: 400px on desktop)
- App logo at top (100x100px, circular with gradient background)
- Form below logo
- "Don't have account? Register" link at bottom

**Components:**

**Logo Container:**
- Size: 100x100px
- Background: Primary gradient
- Border-radius: 20px
- Icon: Pie chart (white, 50px)

**Title:**
- Text (AR): سهمي - Sahmi
- Font-size: 32px
- Font-weight: 700
- Color: Primary green

**Subtitle:**
- Text (AR): مرحباً بك
- Text (EN): Welcome
- Font-size: 16px
- Color: Text secondary

**Email Field:**
- Label: البريد الإلكتروني (Email)
- Type: email
- Icon (left): Envelope icon
- Placeholder: example@email.com
- Helper text: أدخل بريدك الإلكتروني
- Validation: Real-time email validation
- Error message: البريد الإلكتروني غير صحيح (Invalid email)

**Password Field:**
- Label: كلمة المرور (Password)
- Type: password
- Icon (left): Lock icon
- Icon (right): Eye icon (toggle visibility)
- Placeholder: ••••••••
- Helper text: الحد الأدنى 6 أحرف (Minimum 6 characters)
- Validation: Real-time validation
- Error message: كلمة المرور قصيرة جداً (Password too short)

**Login Button:**
- Text: تسجيل الدخول (Login)
- Width: 100%
- Height: 48px
- Background: Primary gradient
- Color: White
- Border-radius: 12px
- Hover effect: Lift shadow
- Loading state: Spinner + "جاري التسجيل..."

**Forgot Password Link:**
- Text: نسيت كلمة المرور؟
- Color: Primary
- Position: Below password field

**Register Link:**
- Text: ليس لديك حساب؟ إنشاء حساب
- Colors: Text secondary + Primary (link)
- Position: Bottom center

---

### **3. Register Screen**

**Layout:**
- Similar to login
- Additional fields for registration

**Fields:**

1. **Full Name**
   - Label: الاسم الكامل (Full Name)
   - Icon: User icon
   - Validation: Required, min 3 characters

2. **Email**
   - Same as login

3. **Phone Number**
   - Label: رقم الجوال (Phone Number)
   - Icon: Phone icon
   - Format: Saudi format (+966)
   - Placeholder: +966 5X XXX XXXX

4. **Password**
   - Same as login

5. **Confirm Password**
   - Label: تأكيد كلمة المرور
   - Must match password
   - Real-time validation

**Register Button:**
- Text: إنشاء حساب (Create Account)
- Same styling as login button

---

### **4. Home Screen (Dashboard)**

**Layout Structure:**
```
├── Top Header
├── User Welcome Section (Gradient background)
├── Search Bar
├── Stats Cards (4 cards in grid)
├── Featured Properties Section
└── Trending Properties Section
```

**Top Header:**
- App name: سهمي (left/right based on language)
- Notification icon (right/left)
  - Badge with count if notifications exist
  - Shows "قريباً" (Coming Soon) snackbar on click

**Welcome Section (Gradient Background):**
- Background: Primary gradient
- Border-radius: 0 0 30px 30px (bottom corners only)
- Padding: 20px

**Content:**
- "مرحباً" (Welcome) - font-size: 16px, color: white
- User name - font-size: 24px, weight: 700, color: white
- Two stat cards (white with opacity 0.2):
  - **Left Card:**
    - Label: إجمالي الاستثمار (Total Investment)
    - Value: XXX,XXX ر.س (format with thousands separator)
    - Icon: Trending up (if positive)
  - **Right Card:**
    - Label: إجمالي العوائد (Total Returns)
    - Value: +XX,XXX ر.س (green if positive, red if negative)
    - Icon: Trending up/down based on value

**Search Bar:**
- Position: Below welcome section, -30px overlap (elevated)
- Background: White
- Border: 1px solid border color
- Border-radius: 12px
- Shadow: Card shadow
- Icon (left): Magnifying glass (Primary color)
- Placeholder: ابحث عن عقار... (Search for property...)
- Clear button (right): X icon (only when text exists)
- Autocomplete: Real-time filtering
- Results: Show below search bar as dropdown

**Stats Cards Grid:**
- Layout: 4 cards in row (desktop), 2x2 (tablet), 2x2 (mobile)
- Gap: 16px
- Card styling:
  - Background: White
  - Border-radius: 12px
  - Padding: 16px
  - Shadow: Card shadow
  - Icon in gradient circle (left/right)

**Cards:**
1. **Total Investment**
   - Icon: Wallet (Primary gradient background)
   - Label: إجمالي الاستثمار
   - Value: XXX,XXX ر.س
   - Trend: +X% (green with up arrow)

2. **Total Returns**
   - Icon: Trending Up (Success gradient)
   - Label: إجمالي العوائد
   - Value: +XX,XXX ر.س
   - Change: +X,XXX ر.س this month

3. **Available Balance**
   - Icon: Credit Card (Info gradient)
   - Label: الرصيد المتاح
   - Value: XX,XXX ر.س
   - Action: "Add Funds" button

4. **Active Properties**
   - Icon: Home (Secondary gradient)
   - Label: العقارات النشطة
   - Value: X عقارات
   - Link: View all

**Featured Properties Section:**
- Title: عقارات مميزة (Featured Properties)
- Layout: Horizontal scrollable cards
- Card width: 300px
- Gap: 16px
- Show 3 cards initially, scroll for more

**Property Card Design:**
- Height: 400px
- Border-radius: 12px
- Shadow: Card shadow
- Overflow: hidden

**Structure:**
```
├── Image Container (180px height)
│   ├── Property Image (cover fit)
│   ├── Funding Badge (top-right): "76% ممول"
│   └── Type Badge (top-left): "تجاري"
├── Content Container (padding: 16px)
│   ├── Property Name (font-size: 18px, weight: 600)
│   ├── Location (icon + text, font-size: 14px, text-secondary)
│   ├── Spacer
│   ├── Price Info Row
│   │   ├── Share Price: "5,000 ر.س / سهم"
│   │   └── Expected Return: "12.5% سنوياً" (success color)
│   ├── Funding Progress Bar
│   │   ├── Bar (height: 8px, border-radius: 4px)
│   │   ├── Fill (primary gradient)
│   │   └── Percentage text: "76% من 25,000,000 ر.س"
│   └── Available Shares: "1,200 سهم متاحة"
```

**Hover Effects:**
- Lift animation (translateY: -4px)
- Enhanced shadow
- Transition: 300ms ease

**Trending Properties Section:**
- Title: عقارات رائجة (Trending Properties)
- Layout: Vertical list (full width cards)
- Same card structure as featured
- Show 5 properties
- "View All" button at bottom

**Loading State:**
- Show shimmer effect for all cards
- Shimmer animation: Left to right gradient sweep
- Duration: 1.5s infinite

**Empty State (Search Results):**
- Icon: Search with X (80px, text-secondary with opacity)
- Title: لا توجد نتائج
- Message: لم نجد عقارات تطابق بحثك. حاول استخدام كلمات مختلفة.
- Center aligned

---

### **5. Marketplace Screen**

**Layout:**
```
├── Header with Tabs
├── Tab Content (Buy Orders / Sell Orders / My Listings)
└── Floating Action Button (+)
```

**Header:**
- Title: السوق (Marketplace)
- Tabs below title:
  - طلبات الشراء (Buy Orders)
  - طلبات البيع (Sell Orders)
  - قائمتي (My Listings)
- Active tab: Bottom border (3px, primary color)

**Listing Card Design:**
- Background: White
- Border-radius: 12px
- Padding: 16px
- Shadow: Card shadow
- Margin-bottom: 16px

**Structure:**
```
├── Horizontal Layout
│   ├── Property Image (80x80px, border-radius: 8px)
│   └── Content (flex: 1, margin-left: 12px)
│       ├── Property Name (font-size: 16px, weight: 600)
│       ├── Seller Name (font-size: 14px, text-secondary)
│       ├── Price Row
│       │   ├── Price per Share: "1,050 ر.س / سهم"
│       │   └── Total Value: "52,500 ر.س"
│       ├── Available Shares: "50 سهماً متاحة"
│       ├── Time Posted: "منذ يومين" (text-secondary, font-size: 12px)
│       └── Action Buttons
│           ├── "شراء" (Primary gradient, full width) for others' listings
│           ├── "إلغاء" (Error outline, full width) for my listings
│           └── "تفاصيل السوق" (Secondary outline) - Show market details sheet
```

**Empty State:**
- **Buy/Sell Orders Tab:**
  - Icon: Store (80px)
  - Title: لا توجد عروض
  - Message: لا توجد عروض متاحة حالياً. تحقق لاحقاً!

- **My Listings Tab:**
  - Icon: Inventory box (80px)
  - Title: لا توجد قائمة
  - Message: لم تقم بإنشاء أي عرض بيع بعد. ابدأ بعرض أسهمك للبيع!
  - Action Button: "إنشاء عرض"

**Floating Action Button:**
- Position: Bottom-right (fixed)
- Size: 56px diameter
- Background: Primary gradient
- Icon: Plus (+)
- Label: "إنشاء عرض"
- Shadow: Elevated
- On click: Show "قريباً - سيتم إضافة هذه الميزة قريباً" snackbar

**Buy/Sell Dialog:**
- Modal dialog (centered)
- Title: شراء أسهم (Buy Shares)
- Content:
  - Property name display
  - Number of shares input
  - Max available display: "Max: 50"
  - Total price calculation (real-time)
- Actions:
  - Cancel button (text)
  - Confirm button (primary)

**Market Details Bottom Sheet:**
(See Property Details section for complete specification)

---

### **6. Portfolio Screen**

**Layout:**
```
├── Header
├── Portfolio Summary Card (Gradient)
├── Performance Chart
└── Properties List
```

**Portfolio Summary Card:**
- Background: Primary gradient
- Border-radius: 16px
- Padding: 24px
- Margin: 16px
- Shadow: Elevated

**Content:**
- **Total Portfolio Value**
  - Label: القيمة الإجمالية للمحفظة
  - Value: XXX,XXX ر.س (font-size: 32px, weight: 700, white)
- **Profit/Loss Row**
  - Value: +XX,XXX ر.س (+X.XX%)
  - Color: Success green if positive, Error red if negative
  - Icon: Trending up/down
  - Font-size: 18px, weight: 600

**Performance Chart:**
- Title: توزيع الاستثمارات (Investment Distribution)
- Type: Pie Chart
- Background: White card
- Border-radius: 16px
- Padding: 20px
- Shadow: Card shadow

**Chart Sections:**
- Each property represented by a slice
- Colors: Use distinct colors from palette
- Legend: Property name + percentage
- Interactive: Hover to show exact amounts

**Properties List:**
- Title: عقاراتي (My Properties)
- Layout: Vertical list of cards

**Property Card:**
```
├── Header Row
│   ├── Property Image (60x60px, border-radius: 8px)
│   └── Info
│       ├── Property Name (font-size: 16px, weight: 600)
│       └── Shares Owned: "25 سهماً" (text-secondary)
├── Divider
├── Stats Grid (3 columns)
│   ├── Avg Purchase Price: "5,000 ر.س"
│   ├── Current Price: "5,200 ر.س"
│   └── Total Dividends: "1,500 ر.س"
├── Investment Details Row
│   ├── Total Invested: "125,000 ر.س"
│   └── Current Value: "130,000 ر.س"
└── Profit/Loss Badge
    └── "+5,000 ر.س (+4%)" (success background if positive)
```

**Empty State:**
- Icon: Pie chart outline (100px)
- Title: لا توجد استثمارات
- Message: لم تقم بأي استثمارات بعد. ابدأ الاستثمار في العقارات الآن!
- Action Button: "استكشف العقارات" (Navigate to Home)

---

### **7. Transactions Screen**

**Layout:**
```
├── Header
├── Filter Tabs
└── Transaction List
```

**Filter Tabs:**
- Horizontal scrollable chips
- Options: الكل (All), شراء (Purchase), بيع (Sale), أرباح (Dividend), إيداع (Deposit), سحب (Withdrawal)
- Active chip: Primary background with white text
- Inactive: Border outline, text-secondary

**Transaction Card:**
```
├── Icon Circle (Left/Right based on language)
│   └── Icon based on type (48px circle with light background)
├── Content (flex: 1)
│   ├── Transaction Type (font-size: 16px, weight: 600)
│   ├── Property Name (font-size: 14px, text-secondary)
│   ├── Date & Time (font-size: 12px, text-secondary)
│   │   └── "15 يناير 2025، 10:30 ص"
│   └── Transaction ID (font-size: 12px, text-secondary)
└── Amount (Right/Left)
    ├── Value (font-size: 18px, weight: 600)
    └── Color: Success (positive), Error (negative), Primary (neutral)
```

**Transaction Types & Icons:**
- **Purchase**: Shopping cart (Primary background)
- **Sale**: Tag (Success background)
- **Dividend**: Coins (Secondary background)
- **Deposit**: Arrow down (Info background)
- **Withdrawal**: Arrow up (Warning background)

**Status Badge:**
- Position: Below transaction ID
- Options: مكتمل (Completed - success), قيد المعالجة (Processing - warning), فشل (Failed - error)
- Small badge with rounded corners

**Empty State:**
- Icon: Receipt with X (80px)
- Title: لا توجد معاملات
- Message: لم تقم بأي معاملات بعد.

**Transaction Details Modal:**
(On card click)
- Modal overlay with centered card
- Full transaction details
- QR code or transaction ID for reference
- Share/Download options

---

### **8. Profile Screen**

**Layout:**
```
├── Header
├── User Info Card (Gradient)
├── Account Menu Section
├── Help & Support Section
└── Logout Button
```

**User Info Card:**
- Background: Primary gradient
- Border-radius: 16px
- Padding: 24px
- Margin: 16px

**Content:**
- **Avatar**
  - Size: 80px diameter
  - Background: White circle
  - Icon: User icon or initials
  - Position: Center top

- **User Name**
  - Font-size: 24px, weight: 700, white
  - Center aligned

- **Email**
  - Font-size: 14px, white with opacity 0.9
  - Center aligned

- **Balance Display**
  - Label: الرصيد المتاح
  - Value: XX,XXX ر.س
  - Font-size: 18px, weight: 600
  - Background: White with opacity 0.2
  - Border-radius: 8px
  - Padding: 12px

- **Verification Badge**
  - Position: Below balance
  - Background: Success (if verified) or Warning (if not)
  - Icon: Checkmark or Warning icon
  - Text: موثق (Verified) or غير موثق (Not Verified)
  - Border-radius: 20px
  - Padding: 8px 16px

**Menu Sections:**

**Account Settings:**
- Card background: White
- Border-radius: 12px
- Padding: 16px
- Shadow: Card shadow

**Menu Items:**

1. **Verify Identity**
   - Icon: Shield with checkmark (Primary)
   - Title: التحقق من الهوية
   - Badge: "قريباً" (Gold, top-right of title)
   - Arrow: Chevron right
   - On click: "قريباً - سيتم إضافة التحقق قريباً"

2. **Language Selection**
   - Icon: Globe (Primary)
   - Title: اللغة (Language)
   - Dropdown: العربية / English
   - No arrow (has dropdown)

3. **Theme Toggle**
   - Icon: Moon/Sun (Primary)
   - Title: الوضع الداكن (Dark Mode)
   - Toggle switch (right side)
   - Working feature (toggle light/dark mode)

4. **Notifications**
   - Icon: Bell (Primary)
   - Title: الإشعارات
   - Badge: "قريباً"
   - Arrow: Chevron right

**Help & Support Section:**
(Same card structure)

1. **Privacy Policy**
   - Icon: Shield (Primary)
   - Title: سياسة الخصوصية
   - Badge: "قريباً"

2. **Terms & Conditions**
   - Icon: Document (Primary)
   - Title: الشروط والأحكام
   - Badge: "قريباً"

3. **Contact Us**
   - Icon: Headset (Primary)
   - Title: اتصل بنا
   - Badge: "قريباً"

**Logout Button:**
- Background: Transparent
- Border: 1px solid Error color
- Color: Error
- Icon: Logout icon
- Text: تسجيل الخروج
- Width: Full width
- Height: 48px
- Margin-top: 16px

**Logout Confirmation Dialog:**
- Title: تسجيل الخروج
- Message: هل أنت متأكد من تسجيل الخروج؟
- Actions: Cancel (text) + Confirm (error button)

---

### **9. Property Details Screen**

**Layout:**
```
├── Image Gallery (Full width)
├── Property Info Section
├── Funding Progress Section
├── Financial Details Grid
├── Description Section
├── Investment Calculator
├── Documents Section
└── Fixed Bottom Bar (Buy Button)
```

**Image Gallery:**
- Full-width carousel
- Height: 300px
- Navigation: Dots at bottom, arrows left/right
- Zoom on click (lightbox)
- Thumbnail strip below (if multiple images)

**Property Info:**
- Padding: 20px
- Property Name (font-size: 24px, weight: 700)
- Type Badge (right side): "تجاري" (Primary background, white text)
- Location (icon + text): "حي العليا، الرياض"

**Funding Progress Section:**
- Background: Primary gradient
- Border-radius: 16px
- Padding: 20px
- Margin: 16px

**Content:**
- **Progress Bar**
  - Height: 12px
  - Background: White with opacity 0.3
  - Fill: White
  - Border-radius: 6px
  - Animated on load

- **Stats Row:**
  - Funded Amount: "19,000,000 ر.س"
  - Target: "/ 25,000,000 ر.س"
  - Percentage: "76%" (large, white, right side)

- **Additional Info:**
  - Available Shares: "1,200 سهم متاح"
  - Share Price: "5,000 ر.س / سهم"

**Financial Details Grid:**
- Layout: 2x2 grid
- Gap: 12px
- Card style for each item

**Items:**
1. **Total Value**
   - Icon: Building
   - Label: القيمة الإجمالية
   - Value: 25,000,000 ر.س

2. **Expected Return**
   - Icon: Trending Up
   - Label: العائد المتوقع
   - Value: 12.5% سنوياً

3. **Investment Period**
   - Icon: Calendar
   - Label: مدة الاستثمار
   - Value: 36 شهراً

4. **Minimum Investment**
   - Icon: Wallet
   - Label: الحد الأدنى للاستثمار
   - Value: 5,000 ر.س (1 سهم)

**Description Section:**
- Title: تفاصيل العقار
- Content: Full property description
- Expandable if long (Show More/Less)

**Investment Calculator:**
- Title: احسب عوائدك
- Background: Light background
- Border-radius: 12px
- Padding: 20px

**Components:**
- **Number of Shares Input**
  - Label: عدد الأسهم
  - Type: Number
  - Min: 1, Max: Available shares
  - Increment/Decrement buttons

- **Real-time Calculation Display:**
  - Investment Amount: XX,XXX ر.س
  - Expected Monthly Return: X,XXX ر.س
  - Expected Annual Return: XX,XXX ر.س
  - Total Expected Return (3 years): XXX,XXX ر.س

**Documents Section:**
- Title: المستندات
- List of documents:
  - Title deed (صك الملكية)
  - Property evaluation (تقييم العقار)
  - Rental contract (عقد الإيجار)
- Each with PDF icon and "View" button
- Badge: "قريباً" on click

**Fixed Bottom Bar:**
- Position: Fixed bottom, full width
- Background: White
- Border-top: 1px solid border color
- Shadow: Elevated (inverted)
- Padding: 16px
- Z-index: 100

**Content:**
- **Left Side:**
  - Selected shares display: "X سهم"
  - Total amount: "XX,XXX ر.س"
  - Font-size: 14px (label), 18px (value, bold)

- **Right Side:**
  - Buy Button
  - Text: "شراء الآن"
  - Background: Primary gradient
  - Width: 150px
  - Height: 48px
  - Border-radius: 12px

---

### **10. Market Details Bottom Sheet**

(Accessed from Marketplace listings "تفاصيل السوق" button)

**Layout:**
- Bottom sheet (mobile) or modal (desktop)
- Height: 85% of screen
- Background: White
- Border-radius: 20px 20px 0 0 (top corners only)
- Handle bar at top (40px width, 4px height, gray)

**Structure:**
```
├── Header
│   ├── Property Name
│   └── Current Price
├── Price History Chart
├── Order Book Section
│   ├── Buy Orders Table
│   └── Sell Orders Table
└── Recent Transactions List
```

**Header:**
- Property name (font-size: 18px, weight: 600)
- Current price (font-size: 24px, weight: 700, primary color)
- 24h change (+X.XX% with color indicator)

**Price History Chart:**
- Title: تاريخ الأسعار (30 يوماً)
- Type: Line chart
- Height: 200px
- X-axis: Dates
- Y-axis: Price (ر.س)
- Line color: Primary gradient
- Fill: Primary with opacity
- Interactive: Hover to show exact values
- Period toggles: 7 days / 30 days / 90 days / 1 year

**Chart Stats (Top of chart):**
- Highest: XX,XXX ر.س (success color)
- Lowest: XX,XXX ر.س (error color)
- Average: XX,XXX ر.س
- Trading Volume: XXX سهم

**Order Book Section:**
- Two tables side by side (desktop) or tabs (mobile)

**Buy Orders Table:**
- Title: طلبات الشراء
- Columns:
  - Price (السعر)
  - Shares (الأسهم)
  - Total (الإجمالي)
- Rows: Up to 10 most recent orders
- Highlight: Best buy price (success background)
- Sort: Highest to lowest

**Sell Orders Table:**
- Title: طلبات البيع
- Same columns as buy orders
- Highlight: Best sell price (error background)
- Sort: Lowest to highest

**Recent Transactions:**
- Title: آخر المعاملات
- List of recent trades
- Each item shows:
  - Price
  - Shares traded
  - Time (relative: "منذ 5 دقائق")
  - Type indicator (buy/sell with color)
- Scrollable list
- Max 20 items

**Empty State (No data):**
- Icon: Chart with X
- Message: لا توجد بيانات متاحة حالياً

---

## 🎭 Animations & Interactions

### **Page Transitions**

```css
/* Fade In */
animation: fadeIn 500ms ease-out;

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

/* Slide Up */
animation: slideUp 400ms ease-out;

@keyframes slideUp {
  from { 
    opacity: 0;
    transform: translateY(20px);
  }
  to { 
    opacity: 1;
    transform: translateY(0);
  }
}

/* Slide Down (for headers) */
animation: slideDown 500ms ease-out;

@keyframes slideDown {
  from { 
    opacity: 0;
    transform: translateY(-20px);
  }
  to { 
    opacity: 1;
    transform: translateY(0);
  }
}
```

### **Card Animations**

**On Load:**
- Staggered fade-in for lists
- Delay: 100ms per item
- Duration: 400ms

**On Hover:**
```css
.card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
  transition: all 300ms ease;
}
```

### **Button Interactions**

**Primary Button:**
```css
.btn-primary {
  transition: all 200ms ease;
}

.btn-primary:hover {
  transform: scale(1.02);
  box-shadow: 0 4px 12px rgba(26, 95, 63, 0.3);
}

.btn-primary:active {
  transform: scale(0.98);
}
```

**Ripple Effect:**
- On click, circular ripple expands from click point
- Color: White with opacity 0.3
- Duration: 600ms

### **Loading States**

**Shimmer Effect:**
```css
@keyframes shimmer {
  0% {
    background-position: -1000px 0;
  }
  100% {
    background-position: 1000px 0;
  }
}

.shimmer {
  background: linear-gradient(
    to right,
    #f0f0f0 0%,
    #f8f8f8 20%,
    #f0f0f0 40%,
    #f0f0f0 100%
  );
  background-size: 1000px 100%;
  animation: shimmer 1.5s infinite linear;
}
```

**Skeleton Screens:**
- Use shimmer for:
  - Property cards
  - Marketplace listings
  - Portfolio items
  - Transaction list
- Show immediately on page load
- Replace with real content smoothly

**Spinner:**
- Circular spinner (32px)
- Primary color
- Used in:
  - Button loading states
  - Dialog loading
  - Inline loading

### **Snackbar/Toast Notifications**

**Position:** Bottom center (mobile), Top-right (desktop)
**Duration:** 2 seconds (dismissible)
**Animation:** Slide up from bottom (mobile), Slide in from right (desktop)

**Types:**
- **Success:** Green background, checkmark icon
- **Error:** Red background, X icon
- **Info:** Blue background, info icon
- **Warning:** Orange background, warning icon
- **Coming Soon:** Gold background, clock icon

**Structure:**
```
├── Icon (24px)
├── Message (flex: 1)
└── Close button (X)
```

### **Modal/Dialog Animations**

**Overlay:**
```css
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

.modal-overlay {
  animation: fadeIn 200ms ease-out;
}
```

**Dialog:**
```css
@keyframes scaleIn {
  from {
    opacity: 0;
    transform: scale(0.9);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

.modal-dialog {
  animation: scaleIn 300ms ease-out;
}
```

**Bottom Sheet:**
```css
@keyframes slideUpSheet {
  from { transform: translateY(100%); }
  to { transform: translateY(0); }
}

.bottom-sheet {
  animation: slideUpSheet 300ms ease-out;
}
```

### **Pull to Refresh**

**States:**
1. **Idle:** No indicator
2. **Pulling:** Show circular progress (grows with pull distance)
3. **Release threshold:** Haptic feedback (if available), ready indicator
4. **Refreshing:** Show spinner with shimmer skeleton
5. **Complete:** Brief success indicator, fade out

**Visual:**
- Circular progress indicator
- Primary color
- Size: 40px
- Position: Top center, 60px from top

---

## 📱 Responsive Design

### **Breakpoints**

```css
/* Mobile */
@media (max-width: 640px) { }

/* Tablet */
@media (min-width: 641px) and (max-width: 1024px) { }

/* Desktop */
@media (min-width: 1025px) { }

/* Large Desktop */
@media (min-width: 1440px) { }
```

### **Layout Adaptations**

**Mobile (< 640px):**
- Single column layout
- Bottom navigation bar (fixed)
- Full-width cards
- Stacked stats (2x2 grid max)
- Hamburger menu for secondary navigation
- Bottom sheets for modals
- Search overlay full screen

**Tablet (641px - 1024px):**
- 2-3 column grid for cards
- Side navigation drawer (collapsible)
- Modal dialogs instead of bottom sheets
- Larger touch targets (48px min)

**Desktop (> 1025px):**
- Max content width: 1200px (centered)
- Persistent sidebar navigation (left)
- 3-4 column grid for cards
- Hover states visible
- Right sidebar for additional info (optional)
- Modal dialogs centered
- Search inline with results dropdown

---

## 🌐 Internationalization (i18n)

### **Language Support**

**Primary:** Arabic (ar-SA)
**Secondary:** English (en-US)

### **RTL Support**

**Arabic (RTL):**
- Text alignment: Right
- Layout direction: RTL
- Icons: Mirror horizontally (arrows, chevrons)
- Navigation: Right side
- Progress bars: Right to left fill
- Number formatting: Arabic-Indic numerals (optional)

**English (LTR):**
- Text alignment: Left
- Layout direction: LTR
- Standard icon orientation
- Navigation: Left side
- Progress bars: Left to right fill
- Number formatting: Western numerals

### **Translation Structure**

Store translations in JSON format:

```json
{
  "ar": {
    "app_name": "سهمي",
    "welcome": "مرحباً",
    "login": "تسجيل الدخول",
    "email": "البريد الإلكتروني",
    "password": "كلمة المرور",
    // ... all strings
  },
  "en": {
    "app_name": "Sahmi",
    "welcome": "Welcome",
    "login": "Login",
    "email": "Email",
    "password": "Password",
    // ... all strings
  }
}
```

### **Number Formatting**

**Currency:**
- Arabic: "١٠٠,٠٠٠ ر.س" (with Arabic numerals)
- English: "100,000 SAR" or "SAR 100,000"
- Thousands separator: Comma
- Decimal separator: Period

**Dates:**
- Arabic: "١٥ يناير ٢٠٢٥" (Hijri calendar optional)
- English: "January 15, 2025" or "15 Jan 2025"
- Time: "10:30 ص" (Arabic), "10:30 AM" (English)

**Percentages:**
- Arabic: "٪١٢.٥" or "12.5%"
- English: "12.5%"

---

## 🔐 Security & Privacy Notes

### **Authentication**

**Storage:**
- JWT tokens in httpOnly cookies (preferred)
- Or localStorage with encryption
- Refresh token for session management

**Session:**
- Auto-logout after 30 minutes inactivity
- Remember me option (extends to 30 days)

**Password Requirements:**
- Minimum 6 characters (will be strengthened with backend)
- Show password strength indicator
- Confirm password matching

### **Data Privacy**

**User Data:**
- No sensitive data in localStorage without encryption
- Clear storage on logout
- Secure API calls (HTTPS only)

**Coming Soon Features:**
- Biometric authentication
- 2FA (Two-Factor Authentication)
- Device management

---

## 🎯 Performance Optimization

### **Loading Strategy**

**Critical CSS:**
- Inline critical CSS in HTML
- Async load non-critical CSS

**Images:**
- Lazy loading for below-fold images
- Use WebP format with fallbacks
- Responsive images (srcset)
- Placeholder blur effect

**Code Splitting:**
- Route-based code splitting
- Load onboarding only on first visit
- Lazy load heavy components (charts)

**Caching:**
- Cache static assets (1 year)
- Cache API responses (appropriate TTL)
- Service worker for offline capability (progressive)

### **Metrics Targets**

- **First Contentful Paint:** < 1.5s
- **Time to Interactive:** < 3s
- **Largest Contentful Paint:** < 2.5s
- **Cumulative Layout Shift:** < 0.1
- **First Input Delay:** < 100ms

---

## 🧪 Testing Requirements

### **Browser Support**

**Desktop:**
- Chrome (last 2 versions)
- Firefox (last 2 versions)
- Safari (last 2 versions)
- Edge (last 2 versions)

**Mobile:**
- iOS Safari (last 2 versions)
- Chrome Android (last 2 versions)
- Samsung Internet (last version)

### **Device Testing**

**Mobile:**
- iPhone 12/13/14 (375x812)
- iPhone 12/13/14 Pro Max (428x926)
- Samsung Galaxy S21/S22 (360x800)
- iPad Air (820x1180)

**Desktop:**
- 1920x1080 (Full HD)
- 1440x900 (MacBook)
- 1366x768 (Common laptop)
- 2560x1440 (QHD)

### **Accessibility**

**WCAG 2.1 Level AA:**
- Color contrast ratio: 4.5:1 (text), 3:1 (large text)
- Keyboard navigation support
- Screen reader support
- Focus indicators visible
- Alt text for all images
- Aria labels for interactive elements
- Semantic HTML

---

## 📚 Component Library Recommendations

### **UI Framework Options**

**React:**
- Material-UI (MUI) - Highly customizable
- Ant Design - Rich component set
- Chakra UI - Accessible, themeable
- Mantine - Modern, full-featured

**Vue:**
- Vuetify - Material Design
- Quasar - Cross-platform
- Element Plus - Enterprise-grade

**Angular:**
- Angular Material - Official
- PrimeNG - Rich component set

### **Chart Library**

**Recommended:**
- Chart.js - Simple, lightweight
- Recharts - React-specific
- ApexCharts - Beautiful, interactive
- D3.js - Maximum flexibility (advanced)

### **Animation Library**

**Recommended:**
- Framer Motion (React) - Declarative animations
- GSAP - High-performance
- Anime.js - Lightweight
- React Spring - Physics-based (React)

### **Form Management**

**Recommended:**
- React Hook Form (React) - Performance focused
- Formik (React) - Popular, mature
- VeeValidate (Vue) - Vue-specific
- Angular Forms (Angular) - Built-in

---

## 🚀 Deployment Considerations

### **Build Process**

```bash
# Install dependencies
npm install

# Development server
npm run dev

# Production build
npm run build

# Preview production build
npm run preview
```

### **Environment Variables**

```env
# API Configuration
VITE_API_BASE_URL=https://api.sahmi.app
VITE_API_TIMEOUT=30000

# App Configuration
VITE_APP_NAME=Sahmi
VITE_DEFAULT_LANGUAGE=ar
VITE_CURRENCY_CODE=SAR

# Feature Flags
VITE_ENABLE_ONBOARDING=true
VITE_ENABLE_HAPTIC=true
VITE_ENABLE_ANALYTICS=false
```

### **Performance Checklist**

- [ ] Minified JavaScript and CSS
- [ ] Tree-shaking enabled
- [ ] Image optimization
- [ ] Lazy loading implemented
- [ ] Code splitting configured
- [ ] Gzip/Brotli compression enabled
- [ ] CDN for static assets
- [ ] Service worker registered
- [ ] Critical CSS inlined
- [ ] Web vitals monitored

---

## 📝 API Integration Guide

### **Base URL**
```
https://api.sahmi.app/v1
```

### **Expected Endpoints**

**Authentication:**
```
POST /auth/login
POST /auth/register
POST /auth/logout
POST /auth/refresh
```

**Properties:**
```
GET /properties (with pagination, filters)
GET /properties/:id
GET /properties/featured
GET /properties/trending
```

**User:**
```
GET /user/profile
PUT /user/profile
GET /user/portfolio
GET /user/transactions
```

**Marketplace:**
```
GET /marketplace/listings
POST /marketplace/listings
DELETE /marketplace/listings/:id
POST /marketplace/purchase/:id
```

**Search:**
```
GET /search/properties?q=keyword
```

### **Request Format**

**Headers:**
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer {token}
Accept-Language: ar-SA | en-US
```

**Response Format:**
```json
{
  "success": true,
  "data": { /* response data */ },
  "message": "Success message",
  "meta": {
    "page": 1,
    "per_page": 10,
    "total": 100
  }
}
```

**Error Format:**
```json
{
  "success": false,
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "Invalid input",
    "details": {
      "email": ["Invalid email format"]
    }
  }
}
```

---

## 🎨 Design Assets Needed

### **Logo**
- SVG format (scalable)
- PNG fallback (512x512, 256x256, 128x128, 64x64)
- Transparent background
- Favicon (32x32, 16x16)
- Apple touch icon (180x180)

### **Icons**
- Use icon library: Heroicons, Feather Icons, or Material Icons
- Consistent stroke width: 2px
- Size: 24x24 default

### **Images**
- Property placeholder images
- User avatar placeholder
- Empty state illustrations
- Onboarding slide backgrounds (optional)

### **Animations**
- Loading spinner (Lottie JSON optional)
- Success checkmark animation
- Empty state animations (subtle)

---

## 🔄 State Management

### **Global State Needed**

**User State:**
- isAuthenticated
- user profile data
- token
- preferences (language, theme)

**App State:**
- loading states
- error states
- notification queue
- modals/dialogs state

**Data State:**
- properties cache
- portfolio data
- marketplace listings
- transactions history

### **Local Storage Items**

```javascript
{
  "onboarding_completed": boolean,
  "theme": "light" | "dark",
  "language": "ar" | "en",
  "auth_token": string,
  "refresh_token": string,
  "user_preferences": object
}
```

---

## ✅ Final Checklist for Web Developer

### **Must Haves:**
- [ ] All 10 screens implemented
- [ ] Arabic (RTL) + English (LTR) support
- [ ] Responsive (mobile, tablet, desktop)
- [ ] Shimmer loading states
- [ ] Empty states for all lists
- [ ] Form validations
- [ ] Smooth animations and transitions
- [ ] "Coming Soon" badges on pending features
- [ ] Search functionality (UI ready)
- [ ] Dark mode support
- [ ] Onboarding flow (first launch)

### **API Integration Ready:**
- [ ] API service layer created
- [ ] Error handling implemented
- [ ] Loading states connected
- [ ] Authentication flow ready
- [ ] Token management setup

### **Performance:**
- [ ] Images optimized
- [ ] Code splitting implemented
- [ ] Lazy loading configured
- [ ] Caching strategy defined

### **Testing:**
- [ ] Cross-browser tested
- [ ] Responsive design tested
- [ ] Accessibility checked
- [ ] Performance metrics measured

---

## 🎯 Success Criteria

**The web version is successful when:**

1. **Visual Parity:** Looks identical to mobile app (95%+ match)
2. **Functionality:** All features work as described
3. **Performance:** Loads fast, smooth animations
4. **Responsive:** Works on all screen sizes
5. **Accessible:** WCAG 2.1 Level AA compliant
6. **RTL Support:** Perfect Arabic layout
7. **Professional:** No bugs, polish matches mobile app

---

## 📞 Support & Questions

**If you need clarification:**
1. Check this document thoroughly
2. Reference the mobile app screenshots
3. Test the mobile app yourself for UX details
4. Ask specific questions about any unclear sections

**Key Resources:**
- Color palette: Use exact hex codes provided
- Components: Follow specifications exactly
- Animations: Match timing and easing
- Spacing: Use the spacing system consistently

---

## 🎉 Good Luck!

This specification should give you (or the AI assistant) everything needed to build a pixel-perfect, feature-complete web version of the Sahmi mobile app.

**Remember:**
- Quality over speed
- User experience is paramount
- Details matter (animations, spacing, colors)
- Test on real devices
- Follow accessibility best practices

**The mobile app is the source of truth - match it as closely as possible!**

---

**Document Version:** 1.0  
**Last Updated:** February 15, 2026  
**Mobile App Version:** 1.0.0 (PoC)

---

*End of Web Frontend Specification*
