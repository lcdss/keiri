module.exports = {
  root: true,
  env: {
    browser: true,
    node: true
  },
  parserOptions: {
    parser: 'babel-eslint',
    ecmaVersion: 8
  },
  extends: [
    'airbnb-base',
    'plugin:vue/recommended'
  ],
  plugins: [
    'vue'
  ],
  rules: {
    'vue/max-attributes-per-line': ['error', {
      'singleline': 8,
      'multiline': {
        'max': 1,
        'allowFirstLine': false
      }
    }],
    'comma-dangle': ['error', 'never'],
    'no-alert': process.env.NODE_ENV === 'production' ? 'error' : 'off',
    'no-debugger': process.env.NODE_ENV === 'production' ? 'error' : 'off',
    'no-shadow': 'off',
    'no-param-reassign': 'off',
    'semi': ['error', 'never'],
    'import/extensions': 'off',
    'import/no-unresolved': 'off',
    'import/no-extraneous-dependencies': 'off'
  }
}
