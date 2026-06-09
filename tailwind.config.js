import defaultTheme from 'tailwindcss/defaultTheme';
import colors from 'tailwindcss/colors';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        screens: {
            '2xsm': '375px',
            xsm: '425px',
            '3xl': '2000px',
            ...defaultTheme.screens,
        },
        extend: {
            colors: {
                current: 'currentColor',
                black: {
                    ...colors.black,
                    DEFAULT: '#1C2434',
                    2: '#010101',
                },
                body: '#64748B',
                bodydark: '#AEB7C0',
                bodydark1: '#DEE4EE',
                bodydark2: '#8A99AF',
                primary: '#3C50E0',
                secondary: '#80CAEE',
                stroke: '#E2E8F0',
                gray: {
                    ...colors.gray,
                    DEFAULT: '#EFF4FB',
                    2: '#F7F9FC',
                    3: '#FAFAFA',
                },
                graydark: '#333A48',
                whiten: '#F1F5F9',
                whiter: '#F5F7FD',
                boxdark: '#24303F',
                'boxdark-2': '#1A222C',
                strokedark: '#2E3A47',
                'form-strokedark': '#3d4d60',
                'form-input': '#1d2a39',
                success: '#219653',
                danger: '#D34053',
                warning: '#FFA70B',
            },
            fontFamily: {
                satoshi: ['Satoshi', 'sans-serif'],
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            spacing: {
                4.5: '1.125rem',
                5.5: '1.375rem',
                6.5: '1.625rem',
                7.5: '1.875rem',
                47.5: '11.875rem',
                62.5: '15.625rem',
                72.5: '18.125rem',
            },
            minWidth: {
                47.5: '11.875rem',
            },
            zIndex: {
                999999: '999999',
                99999: '99999',
                9999: '9999',
                999: '999',
            },
            boxShadow: {
                default: '0px 8px 13px -3px rgba(0, 0, 0, 0.07)',
                card: '0px 1px 3px rgba(0, 0, 0, 0.12)',
                1: '0px 1px 3px rgba(0, 0, 0, 0.08)',
                2: '0px 1px 4px rgba(0, 0, 0, 0.12)',
            },
            dropShadow: {
                1: '0px 1px 0px #E2E8F0',
            },
        },
    },
    plugins: [],
};
