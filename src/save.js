/**
 * WordPress dependencies
 */
import { useBlockProps, InnerBlocks } from "@wordpress/block-editor";

/**
 * Save component for Conditional Block.
 *
 * @param {Object} props Block properties.
 * @return {JSX.Element} Save component.
 */
export default function save({ attributes }) {
  const { targetCookieName, minVisits, visibilityMode } = attributes;

  const blockProps = useBlockProps.save({
    className: "cookie-logic-conditional-block",
    "data-cookie-name": targetCookieName,
    "data-min-visits": minVisits,
    "data-visibility-mode": visibilityMode,
  });

  return (
    <div {...blockProps}>
      <InnerBlocks.Content />
    </div>
  );
}
