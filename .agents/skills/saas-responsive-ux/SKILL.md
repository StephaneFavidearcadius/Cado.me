---
name: saas-responsive-ux
description: saas-responsive-ux skill
---


---
name: saas-responsive-ux
description: Transform any SaaS web application into a fully responsive, production-grade experience across mobile, tablet, laptop, desktop, and large screens. Use this skill when auditing, redesigning, implementing, or fixing responsive behavior in an existing SaaS, dashboard, admin panel, CRM, LMS, community platform, marketplace, or web application. The skill enforces mobile-first UX, layout integrity, accessibility, touch usability, responsive navigation, tables, forms, modals, dashboards, typography, spacing, component behavior, and systematic validation without blindly shrinking desktop layouts.
---

# SaaS Responsive UX — Senior Product & UX Responsive System

## 0. ROLE

You are a senior Product Designer, UX Designer, UI Designer, and Frontend Responsive Engineer with decades of professional experience designing complex digital products.

Your responsibility is not merely to make a SaaS "fit on mobile".

Your responsibility is to make the product **feel intentionally designed for every screen size**.

A responsive interface must preserve:

- hierarchy
- usability
- clarity
- accessibility
- visual rhythm
- interaction quality
- information architecture
- product identity
- performance
- consistency

A desktop interface compressed into a phone is NOT responsive UX.

A mobile interface stretched onto desktop is NOT responsive UX.

Responsive design means that the **interface adapts intelligently to the available space while preserving the user's mental model and task flow**.

---

# 1. PRIMARY OBJECTIVE

When this skill is activated, inspect the SaaS and make the entire experience responsive.

You must consider:

1. Global application shell
2. Header
3. Sidebar
4. Navigation
5. Breadcrumbs
6. Page headers
7. Cards
8. KPI/statistic blocks
9. Dashboards
10. Tables
11. Lists
12. Forms
13. Inputs
14. Selects
15. Search
16. Filters
17. Tabs
18. Buttons
19. Dropdowns
20. Tooltips
21. Modals/dialogs
22. Drawers/sheets
23. Notifications
24. Toasts
25. Pagination
26. Charts
27. Empty states
28. Loading states
29. Error states
30. Authentication pages
31. Settings pages
32. Profile pages
33. Detail pages
34. Multi-column layouts
35. Chat/messaging interfaces
36. File/document interfaces
37. Media interfaces
38. Onboarding flows
39. Pricing/subscription interfaces
40. Footer
41. Mobile-specific interactions
42. Accessibility
43. Keyboard navigation
44. Touch interaction
45. Content overflow
46. Long text
47. Localization expansion
48. Zoom and text scaling
49. Performance implications
50. Cross-device consistency

Do not stop after fixing the homepage.

The goal is **application-wide responsive integrity**.

---

# 2. NON-NEGOTIABLE PRINCIPLES

## 2.1 Mobile-first thinking

Always reason from the smallest practical viewport upward.

Recommended conceptual ranges:

- Small mobile: 320–374px
- Standard mobile: 375–430px
- Large mobile: 431–767px
- Tablet: 768–1023px
- Laptop: 1024–1279px
- Desktop: 1280–1535px
- Large desktop: 1536px+

These are design ranges, not rigid laws.

Do not create dozens of arbitrary breakpoints.

Prefer fluid behavior and a small number of meaningful layout transitions.

---

## 2.2 Never blindly shrink desktop

Do NOT solve responsiveness by:

- reducing every font size
- making everything narrower
- forcing every column to remain visible
- shrinking buttons until they are unusable
- reducing spacing excessively
- hiding important content without a UX decision
- adding horizontal scrolling everywhere
- using `overflow-x: hidden` to conceal broken layouts
- transforming a complex desktop table into an unreadable tiny table

Instead, ask:

> What should this component become when space disappears?

Examples:

Desktop:
Sidebar + content + secondary panel

Mobile:
Top bar + content + secondary panel accessible through a drawer

Desktop:
12-column data table

Mobile:
Card/list representation with primary information + contextual actions

Desktop:
Multi-column form

Mobile:
Single-column grouped sections

Desktop:
Filter sidebar

Mobile:
Filter button → filter drawer

---

# 3. FIRST ACTION: AUDIT BEFORE MODIFYING

Never immediately rewrite the UI.

First inspect the existing project.

Determine:

- framework
- routing
- component architecture
- styling system
- design tokens
- CSS strategy
- UI library
- icon system
- responsive utilities
- existing breakpoints
- reusable components
- page hierarchy
- layout primitives
- data tables
- forms
- dialogs
- navigation
- charts
- custom CSS
- problematic fixed widths
- problematic fixed heights
- absolute positioning
- hardcoded margins
- overflow rules
- viewport assumptions

Identify whether the project uses:

- Next.js
- React
- Vue
- Angular
- Laravel Blade
- PHP
- HTML/CSS/JS
- Tailwind CSS
- CSS Modules
- SCSS
- styled-components
- another system

Respect the existing stack unless there is a compelling technical reason not to.

Do not introduce an unnecessary framework migration.

---

# 4. CREATE A RESPONSIVE INVENTORY

Before implementation, create an internal inventory of the interface.

Group components into:

## Global shell

- App shell
- Header
- Sidebar
- Mobile navigation
- Main content
- Footer

## Navigation

- Primary navigation
- Secondary navigation
- Breadcrumbs
- Tabs
- Pagination

## Content

- Cards
- Lists
- Tables
- Charts
- Statistics
- Detail sections

## Interaction

- Buttons
- Dropdowns
- Menus
- Dialogs
- Drawers
- Tooltips
- Popovers
- Toasts

## Input

- Text inputs
- Search
- Select
- Checkbox
- Radio
- Switch
- Date picker
- File upload
- Rich text editor

## States

- Loading
- Empty
- Error
- Success
- Disabled
- Skeleton
- Offline

Every significant component must have a defined responsive behavior.

---

# 5. RESPONSIVE BEHAVIOR MATRIX

For every major component, reason about:

| Component | Mobile | Tablet | Desktop | Large Desktop |
|---|---|---|---|---|
| Sidebar | Drawer/hidden | Collapsible | Full/collapsible | Full |
| Header | Compact | Compact | Full | Full |
| Cards | 1 column | 2 columns | 3–4 columns | Fluid |
| Table | Cards/list | Compact table | Full table | Full table |
| Filters | Drawer | Drawer/sidebar | Inline/sidebar | Inline/sidebar |
| Form | 1 column | 1–2 columns | 2 columns | 2–3 columns |
| Modal | Near full-screen | Large modal | Centered modal | Centered modal |
| Charts | Simplified | Responsive | Full | Full |
| Navigation | Menu/drawer | Collapsible | Expanded | Expanded |

This matrix is a reasoning tool.

Do not blindly apply it to every product.

---

# 6. CONTAINER SYSTEM

Avoid layouts that become excessively wide on large displays.

Use a coherent content strategy such as:

- full-width shell
- centered content container
- max-width content
- fluid dashboard grid

A typical SaaS content area may use:

- horizontal padding around 16px on small screens
- around 24px on tablet
- around 32px on desktop
- larger controlled gutters on wide screens

Do not hardcode these values everywhere.

Centralize them through the project's design system whenever possible.

---

# 7. WIDTH RULES

Prefer:

- `width: 100%`
- `max-width`
- CSS Grid
- Flexbox
- `minmax()`
- fluid sizing
- responsive constraints

Avoid unnecessary:

- fixed pixel widths
- fixed viewport assumptions
- giant minimum widths
- nested horizontal scrolling
- absolute positioning for primary layout

If a component requires a minimum width, understand why.

---

# 8. HEIGHT RULES

Be especially careful with fixed heights.

Avoid:

```css
height: 500px;
```

when content can naturally grow.

Prefer:

- `min-height`
- intrinsic height
- content-driven layout
- viewport-aware constraints

For full-screen applications, use modern viewport units where appropriate:

- `dvh`
- `svh`
- `lvh`

Be aware of mobile browser UI and safe areas.

---

# 9. TYPOGRAPHY

Responsive typography must preserve hierarchy.

Do not arbitrarily reduce every text size on mobile.

Evaluate:

- page title
- section title
- card title
- body text
- labels
- helper text
- metadata
- buttons
- navigation labels
- table text

Prefer fluid typography where appropriate, for example:

```css
font-size: clamp(min, preferred, max);
```

But do not use `clamp()` everywhere.

Typography must remain:

- readable
- hierarchical
- balanced
- accessible
- consistent

Never sacrifice readability simply to fit more information.

---

# 10. SPACING

Responsive spacing should scale intentionally.

Evaluate:

- page padding
- section spacing
- card padding
- grid gaps
- form gaps
- navigation gaps
- modal padding

Mobile generally needs less spatial waste, but not cramped interfaces.

Avoid both:

- excessive whitespace that hides content
- cramped layouts that create cognitive load

Preserve rhythm.

---

# 11. SIDEBAR RESPONSIVENESS

A SaaS sidebar is one of the most important responsive elements.

Desktop options:

- expanded sidebar
- collapsed sidebar
- persistent navigation

Mobile options:

- drawer
- sheet
- off-canvas menu

Rules:

- never allow the sidebar to crush the main content
- preserve navigation hierarchy
- make active state obvious
- provide an obvious close action on mobile
- lock or manage background interaction correctly when necessary
- support keyboard escape
- manage focus correctly
- prevent body scroll behind the drawer when appropriate

Do not remove navigation simply because the screen is small.

Transform it.

---

# 12. HEADER RESPONSIVENESS

Desktop header may contain:

- breadcrumbs
- search
- actions
- notifications
- account menu
- contextual controls

Mobile header should prioritize:

1. navigation
2. page identity
3. essential actions

Secondary actions may move into:

- overflow menu
- dropdown
- bottom sheet
- contextual drawer

Never let the header become a horizontal overflow disaster.

---

# 13. TABLES

Tables are among the most common SaaS responsive failures.

Never simply shrink a complex table.

For every table, determine whether mobile should use:

### Strategy A — Responsive table

Use when there are few important columns.

### Strategy B — Horizontal scrolling

Use when tabular relationships are essential and cannot reasonably be transformed.

If using horizontal scrolling:

- keep scroll intentional
- preserve row readability
- make overflow discoverable
- avoid nested scroll traps

### Strategy C — Card/list transformation

Use when users primarily need to inspect records rather than compare columns.

Example:

Desktop:

Name | Status | Date | Owner | Revenue | Actions

Mobile:

Card containing:

- Name
- Status
- primary metadata
- key value
- actions

Do not hide essential information without providing another way to access it.

---

# 14. FORMS

Forms must become significantly easier to use on mobile.

Rules:

- prefer single-column layout
- full-width inputs
- clear labels
- sufficient spacing
- visible validation
- useful input types
- appropriate autocomplete
- large touch targets
- avoid tiny controls
- avoid side-by-side fields unless genuinely beneficial

On desktop, multi-column layouts can be used when they improve scanning.

On mobile, prioritize completion speed and clarity.

---

# 15. TOUCH TARGETS

Interactive controls must be comfortable for touch.

Target approximately:

- 44×44 CSS pixels or larger

Especially for:

- icon buttons
- checkboxes
- switches
- menu items
- pagination
- close buttons
- navigation controls

Do not create tiny clickable icons merely because the desktop interface looks clean.

---

# 16. ICON-ONLY CONTROLS

Icon-only buttons require:

- understandable iconography
- accessible label
- tooltip where useful on desktop
- sufficient touch area

Do not rely exclusively on tooltips for mobile.

If an action is important and ambiguous, use visible text.

Use one coherent icon library throughout the product.

Do not replace icons with random Unicode symbols.

---

# 17. BUTTONS

Buttons must adapt without becoming awkward.

Desktop:

- compact
- contextual
- appropriately sized

Mobile:

- full-width where appropriate
- stacked where necessary
- horizontally scrollable action groups only when justified
- primary action remains obvious

Do not make every button full-width by default.

Choose based on task hierarchy.

---

# 18. MODALS AND DIALOGS

Desktop:

- centered dialog
- controlled width
- readable content

Mobile:

- often near full-width
- may become a bottom sheet
- may become full-screen for complex flows

Never allow:

- content clipping
- inaccessible close buttons
- unusable form fields
- dialogs larger than the viewport
- multiple nested scrolling areas without a strong reason

Ensure:

- focus management
- keyboard support
- escape behavior
- screen-reader semantics

---

# 19. DROPDOWNS AND POPOVERS

Check:

- viewport boundaries
- clipping
- positioning
- scroll containers
- touch usability

A dropdown must not render partially outside the viewport.

On mobile, consider replacing complex dropdowns with:

- native select
- sheet
- full-screen selection interface

depending on complexity.

---

# 20. FILTERS

Desktop:

- sidebar
- toolbar
- inline filters

Mobile:

- "Filters" button
- drawer/sheet
- clear/apply actions

Important:

The user must understand:

- how many filters are active
- how to clear them
- whether changes apply instantly or after confirmation

---

# 21. DASHBOARDS

Dashboards often fail because designers attempt to preserve desktop density.

On mobile:

- prioritize the most important KPIs
- stack cards
- allow charts to resize
- simplify secondary visualizations
- avoid tiny graphs
- avoid excessive simultaneous metrics

Ask:

> What decision does this dashboard help the user make?

Prioritize information accordingly.

---

# 22. CHARTS

Charts must remain legible.

Check:

- labels
- legends
- tooltips
- axis text
- data density
- touch interaction

On mobile:

- reduce unnecessary labels
- allow horizontal interaction where useful
- preserve key data
- never make charts microscopic

Do not simply scale a desktop chart down.

---

# 23. MULTI-COLUMN LAYOUTS

Common desktop structure:

```text
Main content | Secondary content
```

On mobile:

```text
Main content
Secondary content
```

But sometimes:

```text
Main content
[View details]
```

is better.

Do not assume stacking is always correct.

Preserve task priority.

---

# 24. CHAT / MESSAGING

Messaging interfaces require special attention.

Check:

- message list height
- composer position
- keyboard behavior
- attachment controls
- conversation navigation
- unread states
- scrolling
- viewport height

Mobile should prioritize the conversation itself.

Conversation list can become a separate navigation screen or drawer.

---

# 25. FILES / DOCUMENTS

For document-heavy SaaS:

Desktop may use:

- sidebar
- file list
- preview panel

Mobile may use:

- list
- dedicated preview screen
- back navigation

Never force three-column document layouts onto a phone.

---

# 26. AUTHENTICATION

Check:

- login
- registration
- password reset
- verification
- MFA
- onboarding

Mobile authentication should be:

- focused
- fast
- vertically organized
- keyboard friendly

Avoid unnecessary decorative elements consuming most of the mobile viewport.

---

# 27. SETTINGS

Settings pages often become unusable on mobile.

Use:

- grouped sections
- clear headings
- stacked controls
- responsive tabs
- mobile navigation between settings categories

Avoid giant desktop forms compressed into one column without hierarchy.

---

# 28. SAFE AREAS

For mobile interfaces involving:

- fixed bottom navigation
- bottom sheets
- fixed action bars
- full-screen controls

consider:

```css
env(safe-area-inset-top)
env(safe-area-inset-bottom)
env(safe-area-inset-left)
env(safe-area-inset-right)
```

when appropriate.

---

# 29. OVERFLOW AUDIT

Search systematically for causes of horizontal overflow.

Common causes:

- fixed widths
- long URLs
- unbroken text
- code blocks
- tables
- oversized images
- charts
- buttons
- flex children without `min-width: 0`
- absolute elements
- transforms
- negative margins

Do not "fix" overflow by blindly adding:

```css
overflow-x: hidden;
```

That can hide real UX defects.

Find the actual cause.

---

# 30. LONG CONTENT

Test:

- very long names
- long email addresses
- long titles
- long URLs
- translated text
- large numbers
- empty values
- missing images
- large descriptions

The design must survive real data, not only perfect demo data.

Use appropriate:

- wrapping
- truncation
- ellipsis
- tooltips
- expandable content

Never truncate critical information without a way to access it.

---

# 31. RESPONSIVE IMAGES

Images should:

- respect their container
- preserve aspect ratio
- avoid layout shifts where possible
- remain readable
- use appropriate cropping

Typical rule:

```css
img {
  max-width: 100%;
  height: auto;
}
```

For application UI, use intentional object-fit behavior where appropriate.

---

# 32. ACCESSIBILITY

Responsive UX is incomplete without accessibility.

Verify:

- semantic HTML
- keyboard navigation
- visible focus
- accessible names
- color contrast
- labels
- form errors
- screen-reader behavior
- dialog semantics
- navigation semantics
- reduced motion preferences

Do not remove focus indicators simply because they look less "premium".

---

# 33. KEYBOARD + MOBILE INPUT BEHAVIOR

Test:

- keyboard opening
- viewport resizing
- focused inputs
- submit behavior
- next-field navigation
- password managers
- autocomplete
- date inputs
- numeric keyboards

Avoid situations where:

- the keyboard hides the active field
- fixed controls cover inputs
- the submit button becomes unreachable

---

# 34. RESPONSIVE NAVIGATION PATTERNS

Choose patterns based on information architecture.

Possible patterns:

### Bottom navigation

For a small set of primary mobile destinations.

### Hamburger + drawer

For larger navigation trees.

### Segmented control

For a small number of mutually exclusive views.

### Tabs

For related content.

### Breadcrumbs

For hierarchical navigation.

### Back navigation

For mobile detail flows.

Do not use every pattern simultaneously.

---

# 35. FIXED AND STICKY ELEMENTS

Audit:

- sticky headers
- sticky sidebars
- sticky table columns
- fixed bottom navigation
- floating buttons
- sticky action bars

Ensure they do not:

- cover content
- block inputs
- consume excessive viewport height
- create nested scroll traps

---

# 36. RESPONSIVE STATES

Every important component should be tested in:

- default
- hover
- focus
- active
- selected
- disabled
- loading
- error
- empty
- success

Hover is not sufficient.

Touch devices do not have conventional hover behavior.

---

# 37. BREAKPOINT DISCIPLINE

Do not create CSS like:

```css
@media (max-width: 1199px) {}
@media (max-width: 1178px) {}
@media (max-width: 1123px) {}
@media (max-width: 1097px) {}
```

unless there is a very specific documented reason.

Breakpoints should represent **layout transitions**, not individual device models.

Think in terms of:

> "The layout no longer has enough space."

not:

> "This is an iPhone."

---

# 38. CONTAINER QUERIES

When the project and browser support allow it, consider container queries for reusable components.

A card should ideally respond to the width available to the card, not only the width of the entire viewport.

This is especially valuable for:

- dashboards
- reusable cards
- widgets
- side panels
- modular SaaS layouts

Do not introduce container queries unnecessarily if the existing architecture is simpler with standard breakpoints.

---

# 39. DESIGN TOKENS

Do not scatter responsive values throughout the codebase.

Prefer centralized tokens for:

- spacing
- typography
- breakpoints
- radii
- shadows
- colors
- container widths
- component dimensions

Respect the project's existing design system.

If no design system exists, establish a lightweight one rather than creating arbitrary values component by component.

---

# 40. VISUAL CONSISTENCY

Responsive behavior must not create visual inconsistency.

Check:

- border radius
- shadows
- colors
- typography
- icon sizes
- spacing
- button heights
- input heights
- card patterns

A mobile redesign should still feel like the same product.

---

# 41. DO NOT DESTROY THE EXISTING DESIGN

Unless explicitly requested:

Do NOT:

- redesign the brand
- randomly change colors
- replace typography
- rewrite content
- change business logic
- remove features
- change routes
- change API contracts
- modify database behavior

The goal is responsive adaptation.

Improve UX only where required for responsive usability.

---

# 42. IMPLEMENTATION STRATEGY

Work in this order:

## Phase 1 — Discovery

Inspect the complete application.

## Phase 2 — Audit

Identify responsive failures.

## Phase 3 — Architecture

Define responsive behavior for global layout and major components.

## Phase 4 — Foundation

Fix:

- viewport
- global CSS
- containers
- typography
- spacing
- grid
- flex behavior
- overflow

## Phase 5 — Global shell

Fix:

- header
- sidebar
- navigation
- main content

## Phase 6 — Core components

Fix:

- buttons
- cards
- forms
- inputs
- dialogs
- dropdowns
- tables
- filters

## Phase 7 — Pages

Process every major page.

## Phase 8 — Edge cases

Test:

- long content
- empty data
- errors
- loading
- large numbers
- narrow screens

## Phase 9 — Accessibility

Run a dedicated accessibility pass.

## Phase 10 — Final validation

Test every major viewport class.

---

# 43. PAGE-BY-PAGE AUDIT

For every page, evaluate:

### Layout

- Does it fit?
- Is the container correct?
- Is content hierarchy preserved?

### Navigation

- Can users move around easily?
- Is the current location obvious?

### Typography

- Is everything readable?
- Is hierarchy preserved?

### Interaction

- Are controls reachable?
- Are touch targets large enough?

### Content

- Is important content visible?
- Is anything accidentally clipped?

### Forms

- Are fields usable?
- Are errors understandable?

### Tables

- Is the chosen mobile strategy appropriate?

### Actions

- Is the primary action obvious?

### States

- What happens when there is no data?

---

# 44. REAL-WORLD TEST MATRIX

At minimum reason through:

### 320px
Extreme narrow mobile.

### 375px
Small modern mobile.

### 390–430px
Common mobile widths.

### 768px
Tablet transition.

### 1024px
Small laptop/tablet landscape.

### 1280px
Standard desktop.

### 1440px
Large desktop.

### 1920px+
Large display.

Also consider:

- browser zoom
- text scaling
- landscape orientation
- touch devices
- keyboard presence
- safe areas

---

# 45. QUALITY GATE

Before declaring the SaaS responsive, verify:

## Layout

- [ ] No accidental horizontal overflow
- [ ] No clipped content
- [ ] No overlapping components
- [ ] No broken grids
- [ ] No unusable fixed widths
- [ ] No broken fixed heights

## Navigation

- [ ] Sidebar transforms correctly
- [ ] Mobile navigation is usable
- [ ] Header remains functional
- [ ] Active navigation state remains clear

## Typography

- [ ] Headings remain readable
- [ ] Body text remains readable
- [ ] No awkward wrapping
- [ ] Long text is handled

## Forms

- [ ] Inputs fit
- [ ] Labels are visible
- [ ] Errors fit
- [ ] Buttons are reachable
- [ ] Keyboard behavior is acceptable

## Tables

- [ ] Every table has an intentional mobile strategy
- [ ] Critical information remains accessible
- [ ] Actions remain usable

## Components

- [ ] Cards adapt
- [ ] Modals adapt
- [ ] Dropdowns adapt
- [ ] Filters adapt
- [ ] Charts adapt
- [ ] Pagination adapts

## Touch

- [ ] Controls have adequate hit areas
- [ ] No tiny critical controls
- [ ] No hover-only functionality

## Accessibility

- [ ] Focus states
- [ ] Keyboard navigation
- [ ] Semantic structure
- [ ] Accessible labels
- [ ] Contrast
- [ ] Reduced motion

## UX

- [ ] Primary actions remain obvious
- [ ] Information hierarchy is preserved
- [ ] Mobile feels intentional
- [ ] Desktop remains polished
- [ ] Tablet does not feel forgotten

---

# 46. ANTI-PATTERNS

Never use these as the primary responsive strategy:

### Anti-pattern 1

"Just add `width: 100%` everywhere."

### Anti-pattern 2

"Just reduce the font size."

### Anti-pattern 3

"Just hide the sidebar."

### Anti-pattern 4

"Just add horizontal scrolling."

### Anti-pattern 5

"Just add `overflow-x: hidden`."

### Anti-pattern 6

"Just use more media queries."

### Anti-pattern 7

"Just stack everything."

### Anti-pattern 8

"Just shrink the desktop table."

### Anti-pattern 9

"Just make every button full-width."

### Anti-pattern 10

"Just use absolute positioning."

Responsive design requires **behavioral adaptation**, not CSS patches.

---

# 47. PRIORITY SYSTEM

When many issues exist, prioritize:

## P0 — Blocking

- inaccessible navigation
- unusable mobile layout
- content inaccessible
- severe overflow
- broken forms
- broken primary actions

## P1 — Major UX

- poor hierarchy
- unusable tables
- bad modal behavior
- navigation friction
- touch problems

## P2 — Visual quality

- spacing inconsistencies
- typography inconsistencies
- minor alignment issues
- cosmetic responsive problems

Fix P0 before P1, and P1 before P2.

---

# 48. CODE QUALITY RULES

When implementing:

- reuse components
- avoid duplicated responsive CSS
- respect existing conventions
- keep selectors predictable
- avoid unnecessary `!important`
- avoid magic numbers
- avoid excessive absolute positioning
- keep responsive logic close to the component when appropriate
- extract shared behavior
- preserve maintainability

Do not solve a responsive problem by creating a second completely separate application unless the UX genuinely requires it.

Prefer shared components with adaptive behavior.

---

# 49. WHEN MOBILE AND DESKTOP SHOULD DIFFER

Responsive does NOT mean identical DOM presentation everywhere.

It is acceptable to change:

- navigation pattern
- ordering
- grouping
- visibility of secondary information
- interaction pattern
- table representation
- modal presentation
- filter presentation

when this improves usability.

The underlying product logic should remain consistent.

---

# 50. FINAL PRINCIPLE

Before every responsive change, ask:

> What is the user's task at this screen size?

Then ask:

> What information is essential?

Then:

> What interaction is fastest and clearest?

Then:

> What can safely collapse, move, transform, or disappear?

Only then choose the CSS or component implementation.

---

# 51. REQUIRED AGENT OUTPUT

After completing a responsive pass, report:

## Responsive Audit Summary

- Overall status
- Main problems found
- Main problems fixed

## Responsive Architecture

- Breakpoint strategy
- Navigation strategy
- Table strategy
- Form strategy
- Modal strategy

## Pages Reviewed

List every reviewed page.

## Components Reviewed

List major components.

## Accessibility

List accessibility improvements.

## Remaining Issues

List anything intentionally not changed and explain why.

## Validation

Report the viewport classes tested and any remaining edge cases.

Never claim something was tested if it was not actually tested.

---

# 52. GOLDEN RULE

The final result must satisfy this sentence:

> **The SaaS should look and behave as though an experienced product team intentionally designed every screen size — not as though a desktop website was squeezed into a phone.**

That is the standard.
