/**
 * Frontend visibility logic for Conditional Blocks
 */
(function () {
  "use strict";

  const COOKIE_NAME = "wp_visit_counts";

  /**
   * Get a cookie value by name.
   *
   * @param {string} name Cookie name.
   * @return {string|null} Cookie value or null if not found.
   */
  function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) {
      return parts.pop().split(";").shift();
    }
    return null;
  }

  /**
   * Get visit counts from cookie.
   *
   * @return {Object} Visit counts object.
   */
  function getVisitCounts() {
    const cookieValue = getCookie(COOKIE_NAME);
    if (!cookieValue) {
      return {};
    }

    try {
      return JSON.parse(decodeURIComponent(cookieValue));
    } catch (e) {
      // Invalid JSON, return empty object.
      return {};
    }
  }

  /**
   * Check if block should be visible based on visit count.
   *
   * @param {HTMLElement} block Block element.
   */
  function checkBlockVisibility(block) {
    const targetCookieName = block.getAttribute("data-cookie-name");
    const minVisits = parseInt(block.getAttribute("data-min-visits"), 10);
    const visibilityMode = block.getAttribute("data-visibility-mode") || "show";

    // Debug logging
    console.log("Cookie Logic Block Debug:", {
      targetCookieName,
      minVisits,
      visibilityMode,
    });

    // If no target cookie name is set, hide the block.
    if (!targetCookieName) {
      block.style.display = "none";
      return;
    }

    // Get visit counts from cookie.
    const visitCounts = getVisitCounts();
    const currentCount = visitCounts[targetCookieName] || 0;

    // Determine if condition is met.
    const conditionMet = currentCount >= minVisits;

    // Debug logging
    console.log("Cookie Logic Decision:", {
      currentCount,
      conditionMet,
      action:
        visibilityMode === "show"
          ? conditionMet
            ? "showing"
            : "hiding"
          : conditionMet
          ? "hiding"
          : "showing",
    });

    // Show or hide based on visibility mode and condition.
    if (visibilityMode === "show") {
      // Show mode: show when condition is met, hide otherwise.
      if (conditionMet) {
        block.style.removeProperty("display");
      } else {
        block.style.setProperty("display", "none", "important");
      }
    } else {
      // Hide mode: hide when condition is met, show otherwise.
      if (conditionMet) {
        block.style.setProperty("display", "none", "important");
      } else {
        block.style.setProperty("display", "block", "important");
      }
    }
  }

  /**
   * Initialize visibility logic for all conditional blocks.
   */
  function initConditionalBlocks() {
    const blocks = document.querySelectorAll(".cookie-logic-conditional-block");
    blocks.forEach(checkBlockVisibility);
  }

  // Run when DOM is ready.
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initConditionalBlocks);
  } else {
    initConditionalBlocks();
  }
})();
