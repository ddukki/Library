# Design Tokens

## Spacing (`tokens/_spacing.scss`)

Based on a 4px grid.

| Variable | Value | Usage |
|----------|-------|-------|
| `$space-xs` | 4px | Tight gaps, icon padding |
| `$space-sm` | 8px | Button padding, small gaps |
| `$space-md` | 16px | Card padding, form gap |
| `$space-lg` | 24px | Section spacing |
| `$space-xl` | 32px | Page section spacing |
| `$space-2xl` | 48px | Major sections |
| `$space-3xl` | 64px | Page margins |
| `$space-section-sm` | 80px | Small section vertical rhythm |
| `$space-section-md` | 120px | Default section vertical rhythm |
| `$space-section-lg` | 160px | Large section vertical rhythm |

## Colors (`tokens/_colors.scss`)

Warm, muted palette for a library app.

| Variable | Value | Usage |
|----------|-------|-------|
| `$color-primary` | #2c3e50 | Headings, nav, primary buttons |
| `$color-primary-light` | #3d566e | Hover states |
| `$color-primary-dark` | #1a252f | Active states |
| `$color-accent` | #d4a574 | Highlights, CTAs |
| `$color-accent-light` | #e8c9a8 | Hover states |
| `$color-accent-dark` | #b8854e | Active states |
| `$color-white` | #ffffff | Backgrounds |
| `$color-bg` | #f4f1ec | Page background |
| `$color-bg-alt` | #eae5dd | Card backgrounds |
| `$color-border` | #d5cec4 | Borders, dividers |
| `$color-text` | #2c2c2c | Body text |
| `$color-text-muted` | #6b6358 | Secondary text |
| `$color-text-inverse` | #f4f1ec | Text on dark |
| `$color-success` | #4a7c59 | Success states |
| `$color-warning` | #c9953b | Warning states |
| `$color-danger` | #9e4e4e | Danger states |
| `$color-info` | #4a6fa5 | Info states |

## Typography (`tokens/_typography.scss`)

Modular scale 1.25 (major third).

| Variable | Value | Usage |
|----------|-------|-------|
| `$font-size-xs` | 0.75rem (12px) | Captions, badges |
| `$font-size-sm` | 0.875rem (14px) | Body small, nav |
| `$font-size-base` | 1rem (16px) | Body text default |
| `$font-size-lg` | 1.125rem (18px) | Body large |
| `$font-size-xl` | 1.25rem (20px) | Subheadings |
| `$font-size-2xl` | 1.5rem (24px) | H2 |
| `$font-size-3xl` | 2rem (32px) | H1 |
| `$font-size-4xl` | 2.5rem (40px) | Display 3 |
| `$font-size-5xl` | 3.25rem (52px) | Display 2 |
| `$font-size-6xl` | 4.5rem (72px) | Display 1 |
| `$font-family-body` | Heebo, Segoe UI, Tahoma, sans-serif | Body, headings |
| `$font-family-heading` | Heebo, Segoe UI, Tahoma, sans-serif | Headings |
| `$font-weight-light` | 300 | Light text |
| `$font-weight-normal` | 400 | Body |
| `$font-weight-medium` | 500 | Medium emphasis |
| `$font-weight-bold` | 700 | Bold |
| `$font-weight-black` | 900 | Display weight |
| `$letter-spacing-tight` | -0.02em | Display text |
| `$letter-spacing-normal` | 0 | Default |
| `$letter-spacing-wide` | 0.05em | Uppercase labels |
| `$line-height-none` | 1 | Display text |
| `$line-height-tight` | 1.2 | Headings |
| `$line-height-base` | 1.5 | Body |
| `$line-height-loose` | 1.75 | Long-form reading |

## Breakpoints (`tokens/_breakpoints.scss`)

| Variable | Value |
|----------|-------|
| `$bp-sm` | 576px |
| `$bp-md` | 768px |
| `$bp-lg` | 992px |
| `$bp-xl` | 1200px |
| `$bp-2xl` | 1400px |

## Borders (`tokens/_borders.scss`)

| Variable | Value | Usage |
|----------|-------|-------|
| `$radius-none` | 0 | No rounding |
| `$radius-sm` | 4px | Buttons, inputs |
| `$radius-md` | 8px | Cards, dropdowns |
| `$radius-lg` | 12px | Modals |
| `$radius-full` | 9999px | Pills, avatars |
| `$border-width-thin` | 1px | Default borders |
| `$border-width-thick` | 2px | Emphasis borders |

## Shadows (`tokens/_shadows.scss`)

| Variable | Value | Usage |
|----------|-------|-------|
| `$shadow-sm` | 0 1px 2px rgba(44, 62, 80, 0.08) | Cards |
| `$shadow-md` | 0 2px 8px rgba(44, 62, 80, 0.1) | Dropdowns |
| `$shadow-lg` | 0 4px 16px rgba(44, 62, 80, 0.12) | Toasts |
| `$shadow-xl` | 0 8px 32px rgba(44, 62, 80, 0.16) | Modals |

## Z-Index (`tokens/_zindex.scss`)

| Variable | Value | Usage |
|----------|-------|-------|
| `$z-dropdown` | 100 | Dropdown menus |
| `$z-sticky` | 200 | Sticky nav |
| `$z-modal-bg` | 300 | Modal backdrop |
| `$z-modal` | 400 | Modal panel |
| `$z-toast` | 500 | Toast notifications |

## Transitions (`tokens/_transitions.scss`)

| Variable | Value | Usage |
|----------|-------|-------|
| `$transition-fast` | 150ms ease | Hover, focus |
| `$transition-base` | 250ms ease | Layout changes |
| `$transition-slow` | 400ms ease | Modal enter |
