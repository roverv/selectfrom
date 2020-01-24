module.exports = {
  prefix: '',
  important: false,
  separator: ':',
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

        "dark-400": "var(--color-dark-400)",
        "dark-500": "var(--color-dark-500)",
        "dark-600": "var(--color-dark-600)",

        "highlight-100": "var(--color-highlight-100)",
        "highlight-400": "var(--color-highlight-400)",
        "highlight-700": "var(--color-highlight-700)",

      },
      // backgroundColor: {
      //   focus: "var(--color-bg-focus)",
      // }
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
