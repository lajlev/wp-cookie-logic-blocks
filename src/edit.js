/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
import {
  InspectorControls,
  useBlockProps,
  InnerBlocks,
} from "@wordpress/block-editor";
import {
  PanelBody,
  TextControl,
  RangeControl,
  Notice,
  SelectControl,
} from "@wordpress/components";

/**
 * Edit component for Conditional Block.
 *
 * @param {Object} props Block properties.
 * @return {JSX.Element} Edit component.
 */
export default function Edit({ attributes, setAttributes }) {
  const { targetCookieName, minVisits, visibilityMode } = attributes;
  const blockProps = useBlockProps({
    className: "cookie-logic-conditional-block",
  });

  return (
    <>
      <InspectorControls>
        <PanelBody
          title={__("Visibility Settings", "cookie-logic-blocks")}
          initialOpen={true}
        >
          <SelectControl
            label={__("Visibility Mode", "cookie-logic-blocks")}
            value={visibilityMode}
            options={[
              {
                label: __("Show when condition is met", "cookie-logic-blocks"),
                value: "show",
              },
              {
                label: __("Hide when condition is met", "cookie-logic-blocks"),
                value: "hide",
              },
            ]}
            onChange={(value) => setAttributes({ visibilityMode: value })}
            help={__(
              "Choose whether to show or hide the block when the visit count threshold is reached",
              "cookie-logic-blocks",
            )}
          />
          <TextControl
            label={__("Target Cookie Name", "cookie-logic-blocks")}
            value={targetCookieName}
            onChange={(value) => setAttributes({ targetCookieName: value })}
            help={__(
              "Enter the cookie name from the page settings (e.g., page_123, page_home)",
              "cookie-logic-blocks",
            )}
            placeholder="page_123"
          />
          <RangeControl
            label={__("Minimum Visits", "cookie-logic-blocks")}
            value={minVisits}
            onChange={(value) => setAttributes({ minVisits: value })}
            min={1}
            max={50}
            help={__(
              visibilityMode === "show"
                ? "Show this block after the visitor has viewed the target page this many times"
                : "Hide this block after the visitor has viewed the target page this many times",
              "cookie-logic-blocks",
            )}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <Notice status="info" isDismissible={false}>
          <strong>{__("Conditional Block", "cookie-logic-blocks")}</strong>
          <br />
          {targetCookieName ? (
            <>
              {visibilityMode === "show"
                ? __("Visible", "cookie-logic-blocks")
                : __("Hidden", "cookie-logic-blocks")}{" "}
              {__("when visitor has viewed", "cookie-logic-blocks")}{" "}
              <code>{targetCookieName}</code>{" "}
              {__("at least", "cookie-logic-blocks")}{" "}
              <strong>{minVisits}</strong>{" "}
              {minVisits === 1
                ? __("time", "cookie-logic-blocks")
                : __("times", "cookie-logic-blocks")}
            </>
          ) : (
            __(
              "Configure visibility settings in the sidebar",
              "cookie-logic-blocks",
            )
          )}
        </Notice>

        <div className="cookie-logic-conditional-block__content">
          <InnerBlocks />
        </div>
      </div>
    </>
  );
}
