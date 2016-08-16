// a simple pubsub module to handle eventing for collaborator
var collaboratorEvents = (function(){
  var topics = {};
  var hOP = topics.hasOwnProperty;

  return {
    subscribe: function(topic, listener) {
      // Create the topic's object if not yet created
      if(!hOP.call(topics, topic)) topics[topic] = [];

      // Add the listener to queue
      var index = topics[topic].push(listener) -1;

      // Provide handle back for removal of topic
      return {
        remove: function() {
          delete topics[topic][index];
        }
      };
    },
    publish: function(topic, info) {
      // If the topic doesn't exist, or there's no listeners in queue, just leave
      if(!hOP.call(topics, topic)) return;

      // Cycle through topics queue, fire!
      topics[topic].forEach(function(item) {
      		item(info != undefined ? info : {});
      });
    }
  };
})();

var CollaboratorViewModel = function(allAvailCollabsJson){
    var vm = this;
    this.allAvailable = allAvailCollabsJson;
    this.collabIndex = {};
    allAvailCollabsJson.map(function(obj){
      vm.collabIndex[obj.user_id] = obj;
    });
    this.hasSelection = false;
    this.selectedCollab = {};
    this.collabSelectElement;
    this.collabSelectJ; // jQuery

    this.setCollab = function(collabId){
        this.selectedCollab = this.collabIndex[collabId];
        this.hasSelection = true;
        collaboratorEvents.publish('collabChanged', {
            hasSelection: this.hasSelection,
            selectedCollab: this.selectedCollab
        });
    };
    this.clearCollab = function(){
        this.selectedCollab = {};
        this.hasSelection = false;
        this.collabSelectElement.selectedIndex = "0";
        collaboratorEvents.publish('collabChanged', {
            hasSelection: this.hasSelection,
            selectedCollab: this.selectedCollab
        });
    };
    this.subscribeCollabChanged = function(listener){
      // Return handle back for removal of topic. use: handle.remove();
      return collaboratorEvents.subscribe('collabChanged', listener);
    };
    this.connectSelectElement = function(selectElementId){
        this.collabSelectElement = document.getElementById(selectElementId);
        this.collabSelectJ = jQuery("#" + selectElementId);
        this.collabSelectJ.on('change', function(e){
          vm.setCollab(this.value);
        });
        jQuery(cvm.allAvailable).each(function(index, o){
            var $option = jQuery("<option/>").attr("value", o.user_id).text(o.user_fullname + " (" + o.user_email + ")");
            vm.collabSelectJ.append($option);
        });
    };
};