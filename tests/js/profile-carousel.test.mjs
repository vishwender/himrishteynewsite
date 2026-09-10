import { test } from 'node:test';
import assert from 'node:assert/strict';
import vm from 'node:vm';
import { readFileSync } from 'node:fs';

test('autoplay advances one card and continues with arrow controls focused', () => {
    const element = () => ({
        children: [], listeners: {}, style: { setProperty() {} }, classList: { add() {} },
        setAttribute() {}, before() {}, after() {},
        append(child) { this.children.push(child); },
        replaceChildren() { this.children = []; },
        addEventListener(name, fn) { this.listeners[name] = fn; },
        contains(target) { return this === target || this.children.includes(target); },
        getBoundingClientRect() { return { width: 180 }; },
    });
    const track = element();
    track.children = Array.from({ length: 10 }, element);
    const slider = element();
    const controls = { '.profile-slider-track': track,
        '.profile-slider-controls': element(), '[data-slider-prev]': element(), '[data-slider-next]': element() };
    slider.querySelector = selector => controls[selector];
    slider.matches = () => true; // Pointer remains over the carousel.
    const document = { hidden: false, activeElement: null, querySelector: () => slider,
        createElement: element, addEventListener() {} };
    let tick;
    const context = vm.createContext({ document,
        window: { innerWidth: 1200, matchMedia: () => ({ matches: false, addEventListener() {} }), addEventListener() {} },
        setInterval: fn => { tick = fn; return 1; }, clearInterval: () => { tick = undefined; }, setTimeout: fn => fn(),
    });
    vm.runInContext(readFileSync('resources/js/home/profile-slider.js', 'utf8').replace('export function', 'function'), context);
    vm.runInContext('initializeProfileSlider()', context);
    assert.equal(typeof tick, 'function');
    tick();
    assert.equal(track.style.transform, 'translateX(-200px)');
    document.activeElement = controls['[data-slider-next]'];
    controls['[data-slider-next]'].listeners.click();
    assert.equal(track.style.transform, 'translateX(-400px)');
    assert.equal(typeof tick, 'function');
    tick();
    assert.equal(track.style.transform, 'translateX(-600px)');
});
