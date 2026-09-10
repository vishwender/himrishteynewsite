import { test } from 'node:test';
import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import vm from 'node:vm';

const source = readFileSync('resources/js/theme.js', 'utf8').replaceAll('export function ', 'function ').replace(/export default \{[\s\S]*?\};/, '');

test('one click toggles once after repeated initialization and persists the preference', () => {
    const listeners = [];
    const stored = new Map();
    const attributes = {};
    const root = { dataset: {}, setAttribute: (_, value) => { root.dataset.theme = value; } };
    const button = { setAttribute: (key, value) => { attributes[key] = value; }, addEventListener: (_, fn) => listeners.push(fn) };
    const context = vm.createContext({
        document: { documentElement: root, querySelectorAll: () => [button], addEventListener: () => {} },
        localStorage: { getItem: key => stored.get(key), setItem: (key, value) => stored.set(key, value) },
        window: { matchMedia: () => ({ matches: true }) },
    });
    vm.runInContext(source, context);
    vm.runInContext('initTheme(); initTheme();', context);
    assert.equal(root.dataset.theme, 'dark');
    assert.equal(listeners.length, 1);
    listeners[0]();
    assert.equal(root.dataset.theme, 'light');
    assert.equal(attributes['aria-pressed'], 'false');
    for (const key of ['site-theme', 'public-theme', 'hr-theme']) assert.equal(stored.get(key), 'light');
    vm.runInContext('initTheme()', context);
    assert.equal(root.dataset.theme, 'light');
    listeners[0]();
    assert.equal(root.dataset.theme, 'dark');
});
