import { trans } from "./helpers";
import planFeatures from "../json/features.json";
import _ from "lodash";

planFeatures.forEach(function (feature, index) {
    feature["name"] = trans(feature["name"]);
    feature["features"].forEach(function (subFeature, index) {
        subFeature["name"] = trans(subFeature["name"]);
        subFeature["helpText"] = trans(subFeature["helpText"]);
    });
});

export const sections = planFeatures;

export const features = _.flatten(sections.map((s) => s.features));

const getFeaturePrice = (feature) => {
    let featureQuantity = feature.value
        ? feature.type == "numeric"
            ? Math.floor(feature.value / feature.chunk)
            : 1
        : 0;
    let featurePriceTotal = featureQuantity * feature.per_chunk_price;
    return featurePriceTotal;
};

export const getPlanPriceObj = (features) =>
    Object.keys(features).reduce(function (sum, key) {
        return sum + getFeaturePrice(features[key]);
    }, 0);

export const getPlanPrice = (features) =>
    _.reduce(
        features,
        function (sum, f) {
            let featureQuantity = f.value
                ? f.type == "numeric"
                    ? Math.floor(f.value / f.chunk)
                    : 1
                : 0;
            let featurePriceTotal = featureQuantity * f.per_chunk_price;
            return sum + featurePriceTotal;
        },
        0
    );

export const getPlanDesc = (features) =>
    features.reduce(function (obj, item) {
        obj[item.key] = item.value;
        return obj;
    }, {});

export const getPlanDescObj = (features) =>
    Object.keys(features).reduce(function (obj, item) {
        obj[features[item]["key"]] = features[item]["value"];
        return obj;
    }, {});

export const mapFeaturesWithPlanDesc = (features, currentPlanDescription) =>
    features.map(function (f) {
        f.value = currentPlanDescription[f.key];
        return f;
    });

export const mapFeaturesWithPlanDescObj = (features, currentPlanDescription) =>
    Object.keys(features).map(function (key) {
        features[key]["value"] = currentPlanDescription[key];
    });

export const convertArrOfObjToObj = (arr, key = "key") =>
    arr.reduce(function (obj, item) {
        obj[item[key]] = item;
        return obj;
    }, {});

export const featuresBoolean = features.filter(
    (feature) => feature.type == "boolean"
);
export const featuresNumeric = features.filter(
    (feature) => feature.type == "numeric"
);

export const featuresObj = convertArrOfObjToObj(features);
