# Menu Styling Guide - Insurance ERP v2.0

## Current Menu Style

The navigation system uses a **modern horizontal mega-menu design** with the following characteristics:

### 1. **Top Header Bar**
- **Gradient Background**: Blue-600 → Blue-700 → Indigo-700
- **Height**: 64px (h-16)
- **Position**: Fixed at top
- **Shadow**: Large shadow for depth

### 2. **Navigation Bar**
- **Background**: Blue-800 with 50% opacity
- **Border**: White with 10% opacity (top border)
- **Overflow**: Horizontal scroll on small screens

### 3. **Menu Items**

#### Default State:
- White text
- Transparent background
- Hover: White background with 10% opacity

#### Active State:
- White background
- Blue-700 text
- Medium shadow (shadow-md)

### 4. **Dropdown Menus**
- **Background**: White
- **Shadow**: 2xl shadow
- **Animation**: Slide down (0.3s ease-out)
- **Max Height**: 600px with scroll
- **Grid Layout**: 2-4 columns (responsive)

### 5. **Color Coding by Module**

| Module | Color | Hover Background | Icon Background |
|--------|-------|------------------|-----------------|
| Dashboard | Blue (blue-600) | blue-50 | blue-100 |
| Masters | Purple (purple-600) | purple-50 | purple-100 |
| Insurance | Green (green-600) | green-50 | green-100 |
| Accounting | Amber (amber-600) | amber-50 | amber-100 |
| Transactions | Cyan (cyan-600) | cyan-50 | cyan-100 |
| HR & Payroll | Rose (rose-600) | rose-50 | rose-100 |
| Reports | Indigo (indigo-600) | indigo-50 | indigo-100 |

## Customization Options

### Option 1: Change Header Color Scheme

```html
<!-- Current: Blue Gradient -->
<div class="bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700">

<!-- Option A: Dark Theme -->
<div class="bg-gradient-to-r from-gray-800 via-gray-900 to-black">

<!-- Option B: Green Theme -->
<div class="bg-gradient-to-r from-green-600 via-green-700 to-emerald-700">

<!-- Option C: Purple Theme -->
<div class="bg-gradient-to-r from-purple-600 via-purple-700 to-indigo-700">

<!-- Option D: Teal Theme -->
<div class="bg-gradient-to-r from-teal-600 via-teal-700 to-cyan-700">
```

### Option 2: Sidebar Menu Style (Alternative)

If you prefer a **left sidebar** instead of horizontal menu:

```html
<!-- Vertical Sidebar Layout -->
<aside class="fixed left-0 top-0 h-screen w-64 bg-gray-900 text-white overflow-y-auto">
    <!-- Logo -->
    <div class="p-6">
        <h1 class="text-2xl font-bold">Insurance ERP</h1>
    </div>

    <!-- Menu Items -->
    <nav class="px-4 space-y-2">
        <!-- Collapsible menu groups -->
    </nav>
</aside>

<!-- Main content with left margin -->
<div class="ml-64">
    <!-- Your content -->
</div>
```

### Option 3: Flat Top Menu (Simpler)

For a **simpler, flat design** without dropdowns:

```html
<nav class="bg-white border-b border-gray-200">
    <div class="flex items-center gap-6 px-6 py-4">
        <a href="#" class="text-gray-700 hover:text-blue-600 font-medium">Dashboard</a>
        <a href="#" class="text-gray-700 hover:text-blue-600 font-medium">Masters</a>
        <a href="#" class="text-gray-700 hover:text-blue-600 font-medium">Insurance</a>
        <!-- ... -->
    </div>
</nav>
```

### Option 4: Tab-Style Menu

```html
<nav class="bg-white border-b-2 border-gray-200">
    <div class="flex items-center px-6">
        <a href="#" class="px-6 py-4 border-b-2 border-blue-600 text-blue-600 font-medium">
            Dashboard
        </a>
        <a href="#" class="px-6 py-4 border-b-2 border-transparent hover:border-gray-300 text-gray-600 hover:text-gray-900 font-medium">
            Masters
        </a>
        <!-- ... -->
    </div>
</nav>
```

## Quick Style Changes

### 1. Change Menu Button Shape

```html
<!-- Current: Rounded -->
class="rounded-lg"

<!-- Alternatives: -->
class="rounded-full"  <!-- Pill shape -->
class="rounded-none"  <!-- Sharp corners -->
class="rounded-md"    <!-- Slightly rounded -->
```

### 2. Change Menu Button Size

```html
<!-- Current: Default padding -->
class="px-4 py-2"

<!-- Larger: -->
class="px-6 py-3"

<!-- Smaller: -->
class="px-3 py-1.5"

<!-- Compact: -->
class="px-2 py-1 text-sm"
```

### 3. Change Icon Size

```html
<!-- Current: Default -->
<i class="fas fa-home"></i>

<!-- Larger: -->
<i class="fas fa-home text-lg"></i>
<i class="fas fa-home text-xl"></i>

<!-- Smaller: -->
<i class="fas fa-home text-sm"></i>
<i class="fas fa-home text-xs"></i>
```

### 4. Change Dropdown Width

```html
<!-- Current: Full width with max-width -->
<div class="max-w-7xl mx-auto">

<!-- Narrower: -->
<div class="max-w-5xl mx-auto">
<div class="max-w-4xl mx-auto">

<!-- Wider: -->
<div class="max-w-full mx-auto">
```

### 5. Change Dropdown Grid Columns

```html
<!-- Current: 2-4 columns -->
class="grid grid-cols-2 md:grid-cols-4 gap-4"

<!-- More columns: -->
class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4"

<!-- Fewer columns: -->
class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"

<!-- List view: -->
class="grid grid-cols-1 gap-2"
```

## Popular Menu Styles

### Style A: Minimal & Clean
```css
- Light gray header (bg-gray-50)
- Dark text (text-gray-900)
- No gradients
- Subtle shadows
- Thin borders
```

### Style B: Bold & Colorful (Current)
```css
- Gradient header (blue)
- Color-coded modules
- Prominent shadows
- White dropdowns
- High contrast
```

### Style C: Dark Mode
```css
- Dark header (bg-gray-900)
- White/light text
- Dark dropdowns (bg-gray-800)
- Subtle highlights
- Low contrast
```

### Style D: Corporate Professional
```css
- Navy blue header
- White secondary bar
- Minimal icons
- Organized dropdowns
- Clean typography
```

## Implementation Guide

### To Change Header Color:

1. Open `/application/views/templates/modern_layout.php`
2. Find line ~82:
```html
<div class="fixed top-0 left-0 right-0 bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 shadow-lg z-50">
```
3. Replace with your preferred color scheme

### To Change Menu Button Style:

1. Find line ~206-212 (Dashboard button example):
```html
<button @click="toggleMenu('dashboard')"
        class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all whitespace-nowrap"
        :class="activeMenu === 'dashboard' ? 'bg-white text-blue-700 shadow-md' : 'text-white hover:bg-white/10'">
```
2. Modify classes as needed

### To Change Dropdown Style:

1. Find line ~278 (Dashboard dropdown example):
```html
<div x-show="activeMenu === 'dashboard'" @click.away="activeMenu = null"
     class="absolute left-0 right-0 bg-white shadow-2xl border-t border-gray-200 animate-slideDown nav-dropdown">
```
2. Modify classes as needed

## Custom CSS Classes Available

You can add custom styles in `/assets/css/main.css`:

```css
/* Custom menu hover effect */
.menu-item-custom {
    transition: all 0.3s ease;
    position: relative;
}

.menu-item-custom::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background: #3b82f6;
    transition: width 0.3s ease;
}

.menu-item-custom:hover::after {
    width: 100%;
}

/* Custom dropdown animation */
.dropdown-custom {
    animation: slideDown 0.3s ease-out;
    backdrop-filter: blur(10px);
}

/* Glassmorphism effect */
.glass-menu {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}
```

## Recommended Combinations

### Combination 1: Professional Corporate
- Header: Navy blue gradient
- Menu: White text, subtle hover
- Dropdowns: Clean white with shadows
- Icons: Minimal, same color

### Combination 2: Modern & Vibrant (Current)
- Header: Blue gradient
- Menu: Color-coded modules
- Dropdowns: White with colored highlights
- Icons: Colorful, per module

### Combination 3: Minimalist
- Header: Light gray
- Menu: Dark text
- Dropdowns: Light gray
- Icons: Monochrome

### Combination 4: Dark Professional
- Header: Dark gray/black
- Menu: Light text
- Dropdowns: Dark with accent colors
- Icons: Light colored

## Need Help?

Would you like me to:
1. Implement a specific menu style?
2. Create a custom color scheme?
3. Change the menu layout (sidebar, tabs, etc.)?
4. Add animations or effects?
5. Create a menu style switcher?

Just let me know what you prefer!
