# DESIGN.md
# PT. Compotec International — Diecast Application System (DAS)

> **Design Direction & Visual Standards**
> This document provides the identity, personality, visual language, and interaction rules for the Diecast Application System (DAS) at PT. Compotec International. All UI/UX implementations must adhere to this direction, filtered through `antislop.md`.

---

## 1. Dials Configuration

```text
Dial: ENERGY 1 / RHYTHM 2 / MOTION 1
```

* **ENERGY 1 (Calm & Utilitarian):** An industrial tool for factory operations, maintenance engineers, and plant supervisors. Information density and readability take precedence over visual spectacle.
* **RHYTHM 2 (Balanced):** Predictable structural grids with deliberate breaks for critical alerts (e.g. Low Stock rows, Inspection Condition cards, two-step generation wizards).
* **MOTION 1 (Subtle & Functional):** Smooth micro-interactions (150ms to 200ms transitions) for hover feedback, dropdowns, and modal state toggles. No looping animations, bouncy spring physics, or gratuitous floating effects.

---

## 2. Brand Identity & Product Context

* **Industry:** Metal Diecasting & Tooling Manufacturing.
* **Primary Users:** Machine Operators (factory floor mobile view), Maintenance Technicians, Shift Supervisors, and Plant IT Administrators.
* **Core Task:** Monitor dieset component lifecycle, track shoot counts (actual vs max shoot), identify critical spare parts shortages (threshold $\le 2$ units), and log preventive maintenance with photographic proof.
* **Tone & Voice:** Engineering-grade, precise, authoritative, and direct. Zero fluff, zero speculative marketing hype.

---

## 3. Color System & Semantic Palette

Adhere strictly to a 3-core-color system + purposeful semantic status colors (R-29):

### Core Palette
* **Industrial Navy / Slate (`#1e2433`, `#262b3c`, `#2a3042`):** Primary structural surface for sidebar and elevated headers. Communicates heavy-duty durability.
* **Neutral Ground (`#f8fafc`, `#f1f5f9`, `#ffffff`):** Clean, high-legibility canvas for data tables and metric panels.
* **Compotec Engineering Blue (`#2563eb`, `#1d4ed8`):** Focal action color for primary buttons, active navigation markers, and verified links.

### Semantic Status Colors (High-Contrast & Actionable)
* **Critical / Danger (`#dc2626` / soft bg `rgba(239, 68, 68, 0.08)`):**
  * Condition: `Replace`
  * Stock: Critical Low Stock ($\le 2$ units)
  * Row indicator: Subtle pink/red tint (`bg-red-50/50`)
* **Maintenance / Attention (`#d97706` / soft bg `rgba(245, 158, 11, 0.1)`):**
  * Condition: `Repair`
  * State: Warning threshold approaching
* **Operational / Safe (`#059669` / soft bg `rgba(16, 185, 129, 0.08)`):**
  * Condition: `OK`
  * Stock: Safe Stock ($> 2$ units)
  * Row indicator: Neutral white / subtle green tint

---

## 4. Typography & Data Presentation

* **Font Family:** `Figtree`, `Inter`, or system sans-serif font stack.
* **Numerals:** Apply tabular figures (`font-variant-numeric: tabular-nums`) to all counter badges, shoot counts, cavity IDs, and timestamps to eliminate column jitter.
* **Hierarchy:**
  * Page Title: `text-xl` or `text-2xl`, font-bold, `text-gray-900`.
  * Section / Modal Header: `text-lg`, font-semibold, `text-gray-800`.
  * Table Header: `text-xs`, font-semibold, uppercase, tracking-wider, `text-gray-500`.
  * Data Cells: `text-sm`, font-medium, `text-gray-800` (code and identifiers in bold).
* **Code / Terminal Monospace:** Forbidden for regular text. Reserved strictly for JSON audit diffs and raw technical identifiers.

---

## 5. Component Affordances & Anti-Slop Constraints

1. **Buttons & Actions:**
   * Primary: Solid Compotec Blue with clear contrast text (`bg-blue-600 text-white hover:bg-blue-700`).
   * Secondary / Filter: Bordered neutral (`bg-white border-gray-300 text-gray-700 hover:bg-gray-50`).
   * Destructive: Crimson (`text-red-600 hover:bg-red-50`).
   * All buttons must have `cursor: pointer`. Disabled buttons must show `cursor: not-allowed` and reduced opacity.
2. **Border Radius:**
   * Use structured `rounded-lg` (8px) for buttons, inputs, and dropdowns.
   * Use `rounded-xl` (12px) for cards, tables, and modals.
   * **FORBIDDEN:** Pill-shaped (`rounded-full`) inputs or feature cards (R-11).
3. **Shadows & Elevation:**
   * Tables and cards remain flat with crisp borders (`border border-gray-200`).
   * Modals and dropdowns use clean elevation (`shadow-lg`). No soft fuzzy blur floating across every card (R-12).
4. **Mobile & Workshop Tablet Readiness:**
   * Minimum touch target size: 44px $\times$ 44px (R-03).
   * Tables must support responsive horizontal scroll wrappers without clipping page layout.
5. **Copywriting Standards:**
   * **FORBIDDEN:** Em dashes (`—`), replace with colons, commas, or parentheses (R-02).
   * **FORBIDDEN:** Generic AI buzzwords (e.g. "Next-Gen", "Seamless", "AI-Powered", "Revolutionary") (R-16).
   * Use precise industrial terminology in clear Indonesian or established technical English (e.g. *Actual Shoot*, *Cavity*, *Dieset Name*, *Request Order SPV*).

---

## 6. Three States Rule (R-27)

Every table, modal, or asynchronous view must render:
1. **Loaded / Normal State:** Clean data with responsive pagination.
2. **Empty State:** Descriptive icon, explanation of why data is empty, and actionable trigger (e.g. "Belum ada riwayat inspeksi pada rentang tanggal ini. Silakan sesuaikan filter tanggal").
3. **Loading / Processing State:** Distinct visual feedback on button submit or table refresh.
