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
  server: {
    port: 80,
    host: true,
    allowedHosts: true,
    cors: true,
  },
});
