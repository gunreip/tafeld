import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
import daisyui from "daisyui";

export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: { extend: {} },
    plugins: [daisyui],
    daisyui: {
        themes: [
            {
                tafeldDark: {
                    primary: "#60A5FA",
                    secondary: "#818CF8",
                    accent: "#34D399",
                    neutral: "#1E293B",
                    "base-100": "#0F172A",
                    info: "#38BDF8",
                    success: "#22C55E",
                    warning: "#EAB308",
                    error: "#EF4444",
                },
            },
            "light",
        ],
    },
};
