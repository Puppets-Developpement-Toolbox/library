import { defineConfig } from "vite";
import tailwindcss from "@tailwindcss/vite";
import liveReload from "vite-plugin-live-reload";

const root = __dirname;
export default defineConfig({
  plugins: [
    tailwindcss(),
    liveReload([
      `${root}/index.php`,
      `${root}/src/**/*`,
      `${root}/templates/**/*`,
    ]),
  ],
  build: {
    manifest: true,
    outDir: `${root}/dist/`,
    rollupOptions: {
      input: {
        main: `${root}/src/css/global.css`,
        global: `${root}/src/js/main.js`,
      },
    },
    target: "es2018",
    write: true,
  },
  server: {
    port: 80,
    host: true,
    allowedHosts: true,
    cors: true,
  },
});
