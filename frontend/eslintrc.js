// .eslintrc.js
module.exports = {
    env: {
        browser: true, // Enable browser globals like window and document
        es2021: true,  // Enable modern ECMAScript features
        node: true,    // Enable Node.js globals
        jest: true,    // Enable Jest globals for testing
    },
    extends: [
        'eslint:recommended',                // ESLint's recommended rules
        'plugin:react/recommended',          // React-specific linting rules
        'plugin:jsx-a11y/recommended',       // Accessibility rules for JSX
        'plugin:prettier/recommended',       // Integrates Prettier with ESLint
    ],
    parserOptions: {
        ecmaFeatures: {
            jsx: true,                          // Enable JSX parsing
        },
        ecmaVersion: 12,                      // Equivalent to ES2021
        sourceType: 'module',                 // Allows for the use of imports
    },
    plugins: [
        'react',                              // React plugin
        'jsx-a11y',                           // JSX Accessibility plugin
        'prettier',                           // Prettier plugin
    ],
    rules: {
        'prettier/prettier': 'error',         // Show Prettier errors as ESLint errors
        'react/react-in-jsx-scope': 'off',    // Not necessary with React 17+
        'no-unused-vars': 'warn',             // Warn about unused variables
        'no-console': 'warn',                 // Warn about console statements
        // Add any additional custom rules here
    },
    settings: {
        react: {
            version: 'detect',                   // Automatically detect the React version
        },
    },
};
