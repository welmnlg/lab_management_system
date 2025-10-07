export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./storage/framework/views/*.php", // Include compiled views
    "./app/Views/**/*.blade.php", // Include views in app directory if any
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
