---
name: Aincrad Interface
colors:
  surface: '#11131a'
  surface-dim: '#11131a'
  surface-bright: '#373941'
  surface-container-lowest: '#0c0e15'
  surface-container-low: '#191b22'
  surface-container: '#1d1f26'
  surface-container-high: '#282a31'
  surface-container-highest: '#33343c'
  on-surface: '#e2e2ec'
  on-surface-variant: '#dac2ad'
  inverse-surface: '#e2e2ec'
  inverse-on-surface: '#2e3038'
  outline: '#a28d79'
  outline-variant: '#544433'
  surface-tint: '#ffb869'
  primary: '#ffc485'
  on-primary: '#482900'
  primary-container: '#ff9d00'
  on-primary-container: '#663c00'
  inverse-primary: '#885200'
  secondary: '#4ae183'
  on-secondary: '#003919'
  secondary-container: '#06bb63'
  on-secondary-container: '#00431f'
  tertiary: '#a5d4ff'
  on-tertiary: '#003351'
  tertiary-container: '#62bbff'
  on-tertiary-container: '#004971'
  error: '#ffb4ab'
  on-error: '#690005'
  error-container: '#93000a'
  on-error-container: '#ffdad6'
  primary-fixed: '#ffdcbb'
  primary-fixed-dim: '#ffb869'
  on-primary-fixed: '#2c1700'
  on-primary-fixed-variant: '#673d00'
  secondary-fixed: '#6bfe9c'
  secondary-fixed-dim: '#4ae183'
  on-secondary-fixed: '#00210c'
  on-secondary-fixed-variant: '#005228'
  tertiary-fixed: '#cce5ff'
  tertiary-fixed-dim: '#92ccff'
  on-tertiary-fixed: '#001d31'
  on-tertiary-fixed-variant: '#004b73'
  background: '#11131a'
  on-background: '#e2e2ec'
  surface-variant: '#33343c'
typography:
  display:
    fontFamily: Sora
    fontSize: 48px
    fontWeight: '800'
    lineHeight: '1.1'
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Sora
    fontSize: 32px
    fontWeight: '600'
    lineHeight: '1.2'
  headline-md:
    fontFamily: Sora
    fontSize: 24px
    fontWeight: '600'
    lineHeight: '1.3'
  body-lg:
    fontFamily: Hanken Grotesk
    fontSize: 18px
    fontWeight: '400'
    lineHeight: '1.6'
  body-md:
    fontFamily: Hanken Grotesk
    fontSize: 16px
    fontWeight: '400'
    lineHeight: '1.5'
  label-caps:
    fontFamily: JetBrains Mono
    fontSize: 12px
    fontWeight: '700'
    lineHeight: '1'
    letterSpacing: 0.1em
  stats-number:
    fontFamily: Sora
    fontSize: 14px
    fontWeight: '700'
    lineHeight: '1'
rounded:
  sm: 0.125rem
  DEFAULT: 0.25rem
  md: 0.375rem
  lg: 0.5rem
  xl: 0.75rem
  full: 9999px
spacing:
  base: 8px
  container-padding: 24px
  gutter: 16px
  section-gap: 80px
  stack-sm: 12px
  stack-md: 24px
---

## Brand & Style

This design system translates the iconic "Link Start" aesthetic into a functional, high-tech interface. The brand personality is immersive, gamified, and precise, targeting users who value a structured, achievement-oriented experience.

The visual style is a fusion of **Glassmorphism** and **Corporate Modern**. It utilizes semi-transparent, frosted "floating" panels that suggest a holographic depth. The UI feels lighter than air yet grounded by technical precision. Every element is designed to feel like a digital overlay on reality, characterized by sharp geometric containers, subtle inner glows, and a vibrant, high-energy accent palette.

## Colors

The palette is rooted in a deep, atmospheric **Dark Charcoal (#1A1C23)** that serves as the "void" behind the holographic interface. 

- **Primary Orange (#FF9D00):** Used exclusively for critical system interactions, call-to-actions, and "System Message" borders. It represents the "Link Start" energy.
- **System Success (Green #2ECC71):** Reserved for health bars (HP), positive status effects, and completed quest states.
- **System Intel (Blue #3498DB):** Used for experience bars (XP), mana/energy, and technical information overlays.
- **Surface Neutrals:** Backgrounds use semi-transparent whites and grays with high-blur backdrops to maintain the "glass" look without sacrificing legibility.

## Typography

The typography system prioritizes a "tech-forward" feel. **Sora** provides the geometric, bold impact needed for headlines and system alerts. **Hanken Grotesk** is used for body content to ensure high readability during long "quest logs" or descriptions. **JetBrains Mono** is utilized for metadata, system logs, and status labels to reinforce the "programmed" nature of the world.

Headlines should often be wrapped in bracket-style flourishes (e.g., `[ LINK START ]`) to mimic the SAO menu style. Labels are almost always uppercase with increased letter spacing for a technical, HUD-like appearance.

## Layout & Spacing

The layout follows a **Fluid Grid** model with a "Center-Out" hierarchy. The primary interface elements float in the center of the viewport, surrounded by ample negative space to maintain the holographic illusion.

- **Desktop:** A 12-column grid with wide 32px gutters. Content is often constrained to a max-width of 1200px to maintain focus.
- **Mobile:** Elements reflow into a single-column stack. Margins reduce to 16px. Glass panels should span the full width of the screen with slight side padding.
- **Rhythm:** We use an 8px baseline. Vertical spacing between different "Quest" or "Skill" cards should be consistent to create a list-like scanning pattern.

## Elevation & Depth

Depth is the core of this design system, achieved through **Glassmorphism** and **Tonal Layering**:

1.  **The Void:** The furthest back layer, a dark gradient.
2.  **Glass Surfaces:** Semi-transparent white (#FFFFFF at 10-15% opacity) with a 20px - 40px Backdrop Blur.
3.  **Active Layer:** Elements currently being interacted with gain a subtle **Inner Glow** of Primary Orange and a 1px solid border.
4.  **Shadows:** Instead of traditional black shadows, use very soft, large-radius blurs that match the hue of the primary or secondary color (e.g., a soft orange glow behind a primary button) to simulate light emission rather than occlusion.

## Shapes

The design uses **Soft (0.25rem)** roundedness for standard components. While the aesthetic is futuristic, it avoids "bubbliness" to maintain a serious, high-stakes tone. 

- **Cards:** Use `rounded-lg` (0.5rem) for a modern feel.
- **Progress Bars:** Use fully rounded (pill-shaped) ends to indicate fluid energy.
- **Buttons:** Use a slightly more aggressive `rounded-xl` or pill-shape to make them stand out as actionable "objects" in the HUD.
- **Decorative Elements:** Use 45-degree chamfered corners on section headers to lean into the sci-fi geometric look.

## Components

- **Glossy Cards:** Glass-morphic containers with a 1px border (#FFFFFF 20% opacity). On hover, the border transitions to Primary Orange.
- **Buttons:** High-contrast capsules. The primary button is solid Orange with white text. Secondary buttons are ghost-style with an orange outline.
- **Progress Bars (HP/XP):** Dual-layered. A dark, recessed track with a glowing, vibrant fill. Add a "glint" effect (a white gradient overlay) to simulate a glass tube.
- **Icon Navigation:** Monoline icons contained within circular glass orbits. Active icons should "pulse" with a soft outer glow.
- **System Alerts:** Centered modal panels with heavy bracket flourishes. The background should increase in blur to 60px to isolate the message from the "game world" behind it.
- **Skill Tree Nodes:** Hexagonal or square tiles with thin glowing connectors that light up as "skills" are unlocked.