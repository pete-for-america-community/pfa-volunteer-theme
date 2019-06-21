(function ($) {
  $(document).ready(function () {
    var output = $("#events-cache-output");
    var displayButton = $("button#displayResults");
    var cacheButton = $("button#clearCache");
    var regenerateButton = $("button#regenerateCache");
    var manualEndpointButton = $("button#manualEndpointButton");
    var manualEndpoint = $("input#manualEndpoint");

    // Attach  initial button actions
    displayButton.click(function (e) {
      displayResults(e, output, displayButton);
    });
    cacheButton.click(function (e) {
      clearCache(e, output, cacheButton);
    });
    regenerateButton.click(function (e) {
      //console.log("regenerateCache clicked");
      clearCache(e, output, cacheButton);
      regenerateCache(e, output, regenerateButton);
    });
    manualEndpointButton.click(function (e) {
      //console.log("regenerateCache clicked");
      manualFetch(e, output, manualEndpointButton, manualEndpoint);
    });
  });

  // Display the current cache state
  function displayResults(e, output) {
    //Disable the button
    e.preventDefault();

    var results = "Temp Results";
    // Submit a GET request to WP
    $.get(
      ajaxurl, //ajaxurl is injected by WP
      {
        action: "retrieve_cache"
      },
      function (response) { }
    )
      .done(function (response) {
        // If successful, update the display area with the response
        output.val(JSON.stringify(response));
      })
      .fail(function () {
        //Process failed requests
        output.val("Error retrieving current cache.");
      });
  }

  // Clear the current cache state
  function clearCache(e, output, cacheButton) {
    cacheButton.find(".spinner").toggleClass("partially-active");
    //Disable the button
    e.preventDefault();

    // Submit a POST request to WP
    $.post(
      ajaxurl, //ajaxurl is injected by WP
      {
        action: "clear_cache"
      },
      function (response) {
        cacheButton
          .find(".spinner")
          .addClass("is-active")
          .removeClass("partially-active");
      }
    )
      .done(function (response) {
        cacheButton
          .find(".spinner")
          .addClass("is-success")
          .removeClass("is-active");

        // If successful, update the display area with the response
        output.val(JSON.stringify(response));
      })
      .fail(function () {
        //Process failed requests
        cacheButton
          .find(".spinner")
          .addClass("is-error")
          .removeClass("is-active");
        output.val("Error: POST request to clear cache failed.");
      });
  }

  // Perform a new API call and update the cache
  function regenerateCache(e, output) {
    //console.log("regenerateCache function triggered");

    //Disable the button
    e.preventDefault();

    // Submit a POST request to WP
    $.post(
      ajaxurl, //ajaxurl is injected by WP
      {
        action: "regenerate_cache",
        update: "TRUE"
      },
      function (response) { }
    )
      .done(function (response) {
        //console.log("success!", response);
        // If successful, update the display area with the response
        output.val(JSON.stringify(response));
      })
      .fail(function () {
        //Process failed requests
        console.log("[Events Collector] Error in WP Admin AJAX");
      });
  }

  // Perform a new API call and update the cache
  function manualFetch(e, output, manualEndpointButton) {
    //console.log("regenerateCache function triggered");

    //Disable the button
    e.preventDefault();

    // Submit a POST request to WP
    $.post(
      ajaxurl, //ajaxurl is injected by WP
      {
        action: "manual_endpoint_fetch",
        update: "FALSE",
        endpoint: manualEndpoint.value()
      },
      function (response) { }
    )
      .done(function (response) {
        //console.log("success!", response);
        // If successful, update the display area with the response
        output.val(JSON.stringify(response));
      })
      .fail(function () {
        //Process failed requests
        console.log("[Events Collector] Error in WP Admin AJAX");
      });
  }
})(jQuery);
