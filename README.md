# CiviShare

CiviShare will enable you to share CiviCRM data between different CiviCRM instances.

**Remark**: Since this project is still in a prototype stage, the documentation in this file is very technical.

## Setup

TODO


## Configuration

TODO


## Peering

Peering in CiviShare happens on two levels:

### Node Peering

Before two CiviShare nodes (i.e. CiviCRM instances with the CiviShare extension) can exchange information, they need to be connected.
For this to happen, they each have to have an entry in the ``civicrm_share_node`` table representing the other node respectively.
Both entries have to also have a unique "common secret" i.e. an identical ``auth_key`` which both nodes use to identify and authorise the connection.  

### Contact Peering

Once the system, i.e. the local node, is peered with at least one other node, you can start peering individual contacts in between those nodes. In this process the system checks for a given local contact whether this contact also exists in the other node. 
If that's the case a link is established between those contacts.
In order to facilitate this peering process, there is a search task that allows you to try and peer any set of local contacts with a peered node.


## Services (API)

The system provides two types of services:

### Peering Services

#### CiviShare.peer
This service allows you to facilitate contact peering, see above. You send a bunch of identifying criteria of the contacts you want to peer, and you will receive a status for each contact.
That status is one of:
- ``NEWLY_PEERED``: contact was identified is is now peered (usually expressed by a contact ID)
- ``INSUFFICIENT_DATA``: there is not enough data submitted to identify the contact
- ``NOT_IDENTIFIED'``: no contact could be identified
- ``AMBIGUOUS``: multiple contacts were identified
- ``ERROR``: some unforeseen error has occurred 


### Change Processing Services 

#### CiviShare.store_changes

Receive a new change from another (authorised) node. This function does *not process* any changes. 
It is required to return as quickly as possible, as it is synchronously called from an external system.

#### CiviShare.send_changes

Send changes that are scheduled for forwarding to those node's respective ``store_changes`` API  

#### CiviShare.process_changes

Process all pending changes that have been received via the ``store_change`` action.


## Change Status

The changes (as stored in ``civicrm_share_change``) have one of the following statuses:
- ``LOCAL``: this was a locally recorded change
- ``PENDING``: this is received change, that has not been applied yet
- ``FORWARD``: this change is ready for propagation to other nodes
- ``DONE``: this change has been fully processed
- ``BUSY``: this change is currently being processed
- ``ERROR``: something went wrong in the processing of this change

A locally detected/recorded change will have the following status flow (excluding ``BUSY`` and ``ERROR``)

``LOCAL`` -> ``FORWARD`` -> ``DONE``

while an externally received change will have the following status flow

``PENDING`` -> ``FORWARD`` -> ``DONE``