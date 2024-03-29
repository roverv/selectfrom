module.exports = {
  prefix: '',
  important: false,
  separator: ':',
  purge: {
    // enabled: true, // for debugging purge on dev
    content: [
      './src/**/*.html',
      './src/**/*.vue',
    ]
  },
  theme: {
    // colors: {},
    extend: {
      textColor: {
        primary: "var(--color-text-primary)",
        focus: "var(--color-text-focus)",
        "on-bg-light": "var(--color-text-on-bg-light)",
      },
      colors: {

        "light-100": "var(--color-light-100)",
        "light-200": "var(--color-light-200)",
        "light-300": "var(--color-light-300)",
        "light-500": "var(--color-light-500)",
        "light-700": "var(--color-light-700)",

        "dark-400": "var(--color-dark-400)",
        "dark-500": "var(--color-dark-500)",
        "dark-600": "var(--color-dark-600)",

        "warning": "var(--color-warning)",

        "highlight-100": "var(--color-highlight-100)",
        "highlight-200": "var(--color-highlight-200)",
        "highlight-300": "var(--color-highlight-300)",
        "highlight-400": "var(--color-highlight-400)",
        "highlight-700": "var(--color-highlight-700)",
        "highlight-800": "var(--color-highlight-800)",

      },
      backgroundColor: {
        "light-50": "var(--color-light-50)",
        "light-70": "var(--color-light-70)",
      }
    }
  },
  variants: {
    appearance: ['responsive'],
    // ...
    zIndex: ['responsive'],
  },
  plugins: [
    // ...
  ],
}
