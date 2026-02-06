module.exports = {
  content: [
    "./resources/js/**/*.{html,js,ts,jsx,tsx}",
    "./resources/views/**/*.blade.php",
  ],
  theme: {
    extend: {
      colors: {
        "m3syslighton-surface": "var(--m3syslighton-surface)",
        "app-athens-gray": "var(--app-athens-gray)",
        "app-mountain-meadow": "var(--app-mountain-meadow)",
        "app-santas-gray": "var(--app-santas-gray)",
        "app-shark": "var(--app-shark)",
        "app-white": "var(--app-white)",
        "app-white-woodsmoke": "var(--app-white-woodsmoke)",
        "rmi-colors-stylessantas-gray": "var(--rmi-colors-stylessantas-gray)",
        "rmi-colors-stylesshark": "var(--rmi-colors-stylesshark)",
        "rmi-colors-styleswhite": "var(--rmi-colors-styleswhite)",
        "rmi-colors-styleswhite-woodsmoke": "var(--rmi-colors-styleswhite-woodsmoke)",
        border: "hsl(var(--border))",
        input: "hsl(var(--input))",
        ring: "hsl(var(--ring))",
        background: "hsl(var(--background))",
        foreground: "hsl(var(--foreground))",
        primary: {
          DEFAULT: "hsl(var(--primary))",
          foreground: "hsl(var(--primary-foreground))",
        },
        secondary: {
          DEFAULT: "hsl(var(--secondary))",
          foreground: "hsl(var(--secondary-foreground))",
        },
        destructive: {
          DEFAULT: "hsl(var(--destructive))",
          foreground: "hsl(var(--destructive-foreground))",
        },
        muted: {
          DEFAULT: "hsl(var(--muted))",
          foreground: "hsl(var(--muted-foreground))",
        },
        accent: {
          DEFAULT: "hsl(var(--accent))",
          foreground: "hsl(var(--accent-foreground))",
        },
        popover: {
          DEFAULT: "hsl(var(--popover))",
          foreground: "hsl(var(--popover-foreground))",
        },
        card: {
          DEFAULT: "hsl(var(--card))",
          foreground: "hsl(var(--card-foreground))",
        },
      },
      fontFamily: {
        "button-medium-14px": "var(--button-medium-14px-font-family)",
        "text-styles-Archivo-black-regular": "var(--text-styles-Archivo-black-regular-font-family)",
        "text-styles-semantic-blockquote": "var(--text-styles-semantic-blockquote-font-family)",
        "text-styles-semantic-button": "var(--text-styles-semantic-button-font-family)",
        "text-styles-semantic-heading-2": "var(--text-styles-semantic-heading-2-font-family)",
        "text-styles-semantic-heading-3": "var(--text-styles-semantic-heading-3-font-family)",
        "text-styles-semantic-heading-4": "var(--text-styles-semantic-heading-4-font-family)",
        "text-styles-semantic-item": "var(--text-styles-semantic-item-font-family)",
        "text-styles-semantic-link": "var(--text-styles-semantic-link-font-family)",
        "text-styles-sora-regular": "var(--text-styles-sora-regular-font-family)",
        "text-styles-sora-semibold": "var(--text-styles-sora-semibold-font-family)",
        sans: [
          "ui-sans-serif",
          "system-ui",
          "sans-serif",
          '"Apple Color Emoji"',
          '"Segoe UI Emoji"',
          '"Segoe UI Symbol"',
          '"Noto Color Emoji"',
        ],
      },
      boxShadow: { "shadow-lg": "var(--shadow-lg)" },
      borderRadius: {
        lg: "var(--radius)",
        md: "calc(var(--radius) - 2px)",
        sm: "calc(var(--radius) - 4px)",
      },
      keyframes: {
        "accordion-down": {
          from: { height: "0" },
          to: { height: "var(--radix-accordion-content-height)" },
        },
        "accordion-up": {
          from: { height: "var(--radix-accordion-content-height)" },
          to: { height: "0" },
        },
      },
      animation: {
        "accordion-down": "accordion-down 0.2s ease-out",
        "accordion-up": "accordion-up 0.2s ease-out",
      },
    },
    container: { center: true, padding: "2rem", screens: { "2xl": "1400px" } },
  },
  plugins: [require("tailwindcss-animate")],
  darkMode: ["class"],
};