/** @type {import('tailwindcss').Config} */
export default {
	presets: [
    require('@marshallu/marsha-tailwind')
  ],
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
