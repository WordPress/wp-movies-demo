import { useContext, useMemo, useEffect } from 'preact/hooks';
import { useSignalEffect } from '@preact/signals';
import { deepSignal } from 'deepsignal';
import { directive } from './hooks';
import { prefetch, navigate, hasClientSideTransitions } from './router';
import { getCallback } from './utils';

const raf = window.requestAnimationFrame;
// Until useSignalEffects is fixed: https://github.com/preactjs/signals/issues/228
const tick = () => new Promise((r) => raf(() => raf(r)));

// Check if current page has client-side transitions enabled.
const clientSideTransitions = hasClientSideTransitions(document.head);

export default () => {
	// wp-context
	directive(
		'context',
		({
			directives: { context },
			props: { children },
			context: { Provider },
		}) => {
			const signals = useMemo(
				() => deepSignal(context.default),
				[JSON.stringify(context.default)]
			);
			return <Provider value={signals}>{children}</Provider>;
		}
	);

	// wp-effect
	directive(
		'effect',
		({ directives: { effect }, element, context: mainContext }) => {
			const context = useContext(mainContext);
			Object.values(effect).forEach((callback) => {
				useSignalEffect(() => {
					const cb = getCallback(callback);
					cb({
						context,
						tick,
						ref: element.ref.current,
						state: window.wpx.state,
					});
				});
			});
		}
	);

	// wp-on:[event]
	directive('on', ({ directives: { on }, element, context: mainContext }) => {
		const context = useContext(mainContext);
		Object.entries(on).forEach(([name, callback]) => {
			element.props[`on${name}`] = (event) => {
				const cb = getCallback(callback);
				cb({ context, event, state: window.wpx.state });
			};
		});
	});

	// wp-class:[classname]
	directive(
		'class',
		({
			directives: { class: className },
			element,
			context: mainContext,
		}) => {
			const context = useContext(mainContext);
			Object.keys(className)
				.filter((n) => n !== 'default')
				.forEach((name) => {
					const cb = getCallback(className[name]);
					const result = cb({ context, state: window.wpx.state });
					if (!result) element.props.class.replace(name, '');
					else if (!element.props.class.includes(name))
						element.props.class += ` ${name}`;
				});
		}
	);

	// wp-bind:[attribute]
	directive(
		'bind',
		({ directives: { bind }, element, context: mainContext }) => {
			const context = useContext(mainContext);
			Object.entries(bind)
				.filter((n) => n !== 'default')
				.forEach(([attribute, callback]) => {
					const cb = getCallback(callback);
					element.props[attribute] = cb({
						context,
						state: window.wpx.state,
					});
				});
		}
	);

	// The `wp-link` directive.
	directive(
		'link',
		({
			directives: {
				link: { default: link },
			},
			props: { href },
			element,
		}) => {
			useEffect(() => {
				// Prefetch the page if it is in the directive options.
				if (clientSideTransitions && link?.prefetch) {
					prefetch(href);
				}
			});

			// Don't do anything if it's falsy.
			if (clientSideTransitions && link !== false) {
				element.props.onclick = async (event) => {
					event.preventDefault();

					// Fetch the page (or return it from cache).
					await navigate(href, link?.scroll);
				};
			}
		}
	);
};
