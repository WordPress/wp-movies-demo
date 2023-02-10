import { deepSignal } from 'deepsignal';

const isObject = (item) =>
	item && typeof item === 'object' && !Array.isArray(item);

export const deepMerge = (target, source) => {
	if (isObject(target) && isObject(source)) {
		for (const key in source) {
			if (isObject(source[key])) {
				if (!target[key]) Object.assign(target, { [key]: {} });
				deepMerge(target[key], source[key]);
			} else {
				Object.assign(target, { [key]: source[key] });
			}
		}
	}
};

const rawState = {};
export const rawStore = { state: deepSignal(rawState) };

if (typeof window !== 'undefined') window.store = rawStore;

export const store = ({ state, ...block }) => {
	deepMerge(rawStore, block);
	deepMerge(rawState, state);
};
