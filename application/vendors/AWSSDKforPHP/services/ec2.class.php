<?php
/*
 * Copyright 2010-2013 Amazon.com, Inc. or its affiliates. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License").
 * You may not use this file except in compliance with the License.
 * A copy of the License is located at
 *
 *  http://aws.amazon.com/apache2.0
 *
 * or in the "license" file accompanying this file. This file is distributed
 * on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
 * express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 */

/**
 * Amazon Elastic Compute Cloud (Amazon EC2) is a web service that provides resizable compute
 * capacity in the cloud. It is designed to make web-scale computing easier for developers.
 *  
 * Amazon EC2's simple web service interface allows you to obtain and configure capacity with
 * minimal friction. It provides you with complete control of your computing resources and lets
 * you run on Amazon's proven computing environment. Amazon EC2 reduces the time required to
 * obtain and boot new server instances to minutes, allowing you to quickly scale capacity, both
 * up and down, as your computing requirements change. Amazon EC2 changes the economics of
 * computing by allowing you to pay only for capacity that you actually use. Amazon EC2 provides
 * developers the tools to build failure resilient applications and isolate themselves from common
 * failure scenarios.
 *  
 * Visit <a href="http://aws.amazon.com/ec2/">http://aws.amazon.com/ec2/</a> for more information.
 *
 * @version 2013.03.14
 * @license See the included NOTICE.md file for complete information.
 * @copyright See the included NOTICE.md file for complete information.
 * @link http://aws.amazon.com/ec2/ Amazon EC2
 * @link http://aws.amazon.com/ec2/documentation/ Amazon EC2 documentation
 */
class AmazonEC2 extends CFRuntime
{
	/*%******************************************************************************************%*/
	// CLASS CONSTANTS

	/**
	 * Specify the queue URL for the United States East (Northern Virginia) Region.
	 */
	const REGION_US_E1 = 'ec2.us-east-1.amazonaws.com';

	/**
	 * Specify the queue URL for the United States East (Northern Virginia) Region.
	 */
	const REGION_VIRGINIA = self::REGION_US_E1;

	/**
	 * Specify the queue URL for the United States West (Northern California) Region.
	 */
	const REGION_US_W1 = 'ec2.us-west-1.amazonaws.com';

	/**
	 * Specify the queue URL for the United States West (Northern California) Region.
	 */
	const REGION_CALIFORNIA = self::REGION_US_W1;

	/**
	 * Specify the queue URL for the United States West (Oregon) Region.
	 */
	const REGION_US_W2 = 'ec2.us-west-2.amazonaws.com';

	/**
	 * Specify the queue URL for the United States West (Oregon) Region.
	 */
	const REGION_OREGON = self::REGION_US_W2;

	/**
	 * Specify the queue URL for the Europe West (Ireland) Region.
	 */
	const REGION_EU_W1 = 'ec2.eu-west-1.amazonaws.com';

	/**
	 * Specify the queue URL for the Europe West (Ireland) Region.
	 */
	const REGION_IRELAND = self::REGION_EU_W1;

	/**
	 * Specify the queue URL for the Asia Pacific Southeast (Singapore) Region.
	 */
	const REGION_APAC_SE1 = 'ec2.ap-southeast-1.amazonaws.com';

	/**
	 * Specify the queue URL for the Asia Pacific Southeast (Singapore) Region.
	 */
	const REGION_SINGAPORE = self::REGION_APAC_SE1;

	/**
	 * Specify the queue URL for the Asia Pacific Southeast (Singapore) Region.
	 */
	const REGION_APAC_SE2 = 'ec2.ap-southeast-2.amazonaws.com';

	/**
	 * Specify the queue URL for the Asia Pacific Southeast (Singapore) Region.
	 */
	const REGION_SYDNEY = self::REGION_APAC_SE2;

	/**
	 * Specify the queue URL for the Asia Pacific Northeast (Tokyo) Region.
	 */
	const REGION_APAC_NE1 = 'ec2.ap-northeast-1.amazonaws.com';

	/**
	 * Specify the queue URL for the Asia Pacific Northeast (Tokyo) Region.
	 */
	const REGION_TOKYO = self::REGION_APAC_NE1;

	/**
	 * Specify the queue URL for the United States GovCloud Region.
	 */
	const REGION_US_GOV1 = 'ec2.us-gov-west-1.amazonaws.com';

	/**
	 * Specify the queue URL for the South America (Sao Paulo) Region.
	 */
	const REGION_SA_E1 = 'ec2.sa-east-1.amazonaws.com';

	/**
	 * Specify the queue URL for the South America (Sao Paulo) Region.
	 */
	const REGION_SAO_PAULO = self::REGION_SA_E1;

	/**
	 * Default service endpoint.
	 */
	const DEFAULT_URL = self::REGION_US_E1;


	/*%******************************************************************************************%*/
	// CONSTRUCTOR

	/**
	 * Constructs a new instance of <AmazonEC2>.
	 *
	 * @param array $options (Optional) An associative array of parameters that can have the following keys: <ul>
	 * 	<li><code>certificate_authority</code> - <code>boolean</code> - Optional - Determines which Cerificate Authority file to use. A value of boolean <code>false</code> will use the Certificate Authority file available on the system. A value of boolean <code>true</code> will use the Certificate Authority provided by the SDK. Passing a file system path to a Certificate Authority file (chmodded to <code>0755</code>) will use that. Leave this set to <code>false</code> if you're not sure.</li>
	 * 	<li><code>credentials</code> - <code>string</code> - Optional - The name of the credential set to use for authentication.</li>
	 * 	<li><code>default_cache_config</code> - <code>string</code> - Optional - This option allows a preferred storage type to be configured for long-term caching. This can be changed later using the <set_cache_config()> method. Valid values are: <code>apc</code>, <code>xcache</code>, or a file system path such as <code>./cache</code> or <code>/tmp/cache/</code>.</li>
	 * 	<li><code>key</code> - <code>string</code> - Optional - Your AWS key, or a session key. If blank, the default credential set will be used.</li>
	 * 	<li><code>secret</code> - <code>string</code> - Optional - Your AWS secret key, or a session secret key. If blank, the default credential set will be used.</li>
	 * 	<li><code>token</code> - <code>string</code> - Optional - An AWS session token.</li></ul>
	 * @return void
	 */
	public function __construct(array $options = array())
	{
		$this->api_version = '2013-02-01';
		$this->hostname = self::DEFAULT_URL;
		$this->auth_class = 'AuthV2Query';

		return parent::__construct($options);
	}


	/*%******************************************************************************************%*/
	// STATE CONSTANTS

	const STATE_PENDING = 0;
	const STATE_RUNNING = 16;
	const STATE_SHUTTING_DOWN = 32;
	const STATE_TERMINATED = 48;
	const STATE_STOPPING = 64;
	const STATE_STOPPED = 80;


	/*%******************************************************************************************%*/
	// INSTANCE CONSTANTS

	// Standard
	const INSTANCE_MICRO = 't1.micro';
	const INSTANCE_SMALL = 'm1.small';
	const INSTANCE_MEDIUM = 'm1.medium';
	const INSTANCE_LARGE = 'm1.large';
	const INSTANCE_XLARGE = 'm1.xlarge';

	// High Memory
	const INSTANCE_HIGH_MEM_XLARGE = 'm2.xlarge';
	const INSTANCE_HIGH_MEM_2XLARGE = 'm2.2xlarge';
	const INSTANCE_HIGH_MEM_4XLARGE = 'm2.4xlarge';
	const INSTANCE_M3_XLARGE = 'm3.xlarge';
	const INSTANCE_M3_4XLARGE = 'm3.4xlarge';

	// High CPU
	const INSTANCE_HIGH_CPU_MEDIUM = 'c1.medium';
	const INSTANCE_HIGH_CPU_XLARGE = 'c1.xlarge';

	// Cluster
	const INSTANCE_CLUSTER_4XLARGE = 'cc1.4xlarge';
	const INSTANCE_CLUSTER_8XLARGE = 'cc2.8xlarge';
	const INSTANCE_CLUSTER_GPU_XLARGE = 'cg1.4xlarge';

	// High I/O
	const INSTANCE_HIGH_IO_4XLARGE = 'hi1.4xlarge';

	// High Storage
	const INSTANCE_STORAGE_8XLARGE = 'hs1.8xlarge';


	/*%******************************************************************************************%*/
	// SETTERS

	/**
	 * This allows you to explicitly sets the region for the service to use.
	 *
	 * @param string $region (Required) The region to explicitly set. Available options are <REGION_US_E1>, <REGION_US_W1>, <REGION_US_W2>, <REGION_EU_W1>, <REGION_APAC_SE1>, <REGION_APAC_SE2>, <REGION_APAC_NE1>, <REGION_US_GOV1>, <REGION_SA_E1>.
	 * @return $this A reference to the current instance.
	 */
	public function set_region($region)
	{
		// @codeCoverageIgnoreStart
		$this->set_hostname($region);
		return $this;
		// @codeCoverageIgnoreEnd
	}


	/*%******************************************************************************************%*/
	// SERVICE METHODS

	/**
	 * Activates a specific number of licenses for a 90-day period. Activations can be done against a
	 * specific license ID.
	 *
	 * @param string $license_id (Required) Specifies the ID for the specific license to activate against.
	 * @param integer $capacity (Required) Specifies the additional number of licenses to activate.
	 * @param array $opt (Optional) An associative array of parameters that can have the following keys: <ul>
	 * 	<li><code>curlopts</code> - <code>array</code> - Optional - A set of values to pass directly into <code>curl_setopt()</code>, where the key is a pre-defined <code>CURLOPT_*</code> constant.</li>
	 * 	<li><code>returnCurlHandle</code> - <code>boolean</code> - Optional - A private toggle specifying that the cURL handle be returned rather than actually completing the request. This toggle is useful for manually managed batch requests.</li></ul>
	 * @return CFResponse A <CFResponse> object containing a parsed HTTP response.
	 */
	public function activate_license($license_id, $capacity, $opt = null)
	{
		if (!$opt) $opt = array();
		$opt['LicenseId'] = $license_id;
		$opt['Capacity'] = $capacity;
		
		return $this->authenticate('ActivateLicense', $opt);
	}

	/**
	 * The AllocateAddress operation acquires an elastic IP address for use with your account.
	 *
	 * @param array $opt (Optional) An associative array of parameters that can have the following keys: <ul>
	 * 	<li><code>Domain</code> - <code>string</code> - Optional - Set to <code>vpc</code> to allocate the address to your VPC. By default, will allocate to EC2. [Allowed values: <code>vpc</code>, <code>standard</code>]</li>
	 * 	<li><code>curlopts</code> - <code>array</code> - Optional - A set of values to pass directly into <code>curl_setopt()</code>, where the key is a pre-defined <code>CURLOPT_*</code> constant.</li>
	 * 	<li><code>returnCurlHandle</code> - <code>boolean</code> - Optional - A private toggle specifying that the cURL handle be returned rather than actually completing the request. This toggle is useful for manually managed batch requests.</li></ul>
	 * @return CFResponse A <CFResponse> object containing a parsed HTTP response.
	 */
	public function allocate_address($opt = null)
	{
		if (!$opt) $opt = array();
				
		return $this->authenticate('AllocateAddress', $opt);
	}

	/**
	 * 
	 *
	 * @param string $network_interface_id (Required) 
	 * @param array $opt (Optional) An associative array of parameters that can have the following keys: <ul>
	 * 	<li><code>PrivateIpAddress</code> - <code>string|array</code> - Optional -  Pass a string for a single value, or an indexed array for multiple values.</li>
	 * 	<li><code>SecondaryPrivateIpAddressCount</code> - <code>integer</code> - Optional - </li>
	 * 	<li><code>AllowReassignment</code> - <code>boolean</code> - Optional - </li>
	 * 	<li><code>curlopts</code> - <code>array</code> - Optional - A set of values to pass directly into <code>curl_setopt()</code>, where the key is a pre-defined <code>CURLOPT_*</code> constant.</li>
	 * 	<li><code>returnCurlHandle</code> - <code>boolean</code> - Optional - A private toggle specifying that the cURL handle be returned rather than actually completing the request. This toggle is useful for manually managed batch requests.</li></ul>
	 * @return CFResponse A <CFResponse> object containing a parsed HTTP response.
	 */
	public function assign_private_ip_addresses($network_interface_id, $opt = null)
	{
		if (!$opt) $opt = array();
		$opt['NetworkInterfaceId'] = $network_interface_id;
		
		// Optional list (non-map)
		if (isset($opt['PrivateIpAddress']))
		{
			$opt = array_merge($opt, CFComplexType::map(array(
				'PrivateIpAddress' => (is_array($opt['PrivateIpAddress']) ? $opt['PrivateIpAddress'] : array($opt['PrivateIpAddress']))
			)));
			unset($opt['PrivateIpAddress']);
		}

		return $this->authenticate('AssignPrivateIpAddresses', $opt);
	}

	/**
	 * The AssociateAddress operation associates an elastic IP address with an instance.
	 *  
	 * If the IP address is currently assigned to another instance, the IP address is assigned to the
	 * new instance. This is an idempotent operation. If you enter it more than once, Amazon EC2 does
	 * not return an error.
	 *
	 * @param string $instance_id (Required) The instance to associate with the IP address.
	 * @param string $public_ip (Required) IP address that you are assigning to the instance.
	 * @param array $opt (Optional) An associative array of parameters that can have the following keys: <ul>
	 * 	<li><code>AllocationId</code> - <code>string</code> - Optional - The allocation ID that AWS returned when you allocated the elastic IP address for use with Amazon VPC.</li>
	 * 	<li><code>NetworkInterfaceId</code> - <code>string</code> - Optional - </li>
	 * 	<li><code>PrivateIpAddress</code> - <code>string</code> - Optional - </li>
	 * 	<li><code>AllowReassociation</code> - <code>boolean</code> - Optional - </li>
	 * 	<li><code>curlopts</code> - <code>array</code> - Optional - A set of values to pass directly into <code>curl_setopt()</code>, where the key is a pre-defined <code>CURLOPT_*</code> constant.</li>
	 * 	<li><code>returnCurlHandle</code> - <code>boolean</code> - Optional - A private toggle specifying that the cURL handle be returned rather than actually completing the request. This toggle is useful for manually managed batch requests.</li></ul>
	 * @return CFResponse A <CFResponse> object containing a parsed HTTP response.
	 */
	public function associate_address($instance_id, $public_ip, $opt = null)
	{
		if (!$opt) $opt = array();
		$opt['InstanceId'] = $instance_id;
		$opt['PublicIp'] = $public_ip;
		
		return $this->authenticate('AssociateAddress', $opt);
	}

	/**
	 * Associates a set of DHCP options (that you've previously created) with the specified VPC. Or,
	 * associates the default DHCP options with the VPC. The default set consists of the standard EC2
	 * host name, no domain name, no DNS server, no NTP server, and no NetBIOS server or node type.
	 * After you associate the options with the VPC, any existing instances and all new instances that
	 * you launch in that VPC use the options. For more information about the supported DHCP options
	 * and using them with Amazon VPC, go to Using DHCP Options in the Amazon Virtual Private Cloud
	 * Developer Guide.
	 *
	 * @param string $dhcp_options_id (Required) The ID of the DHCP options to associate with the VPC. Specify "default" to associate the default DHCP options with the VPC.
	 * @param string $vpc_id (Required) The ID of the VPC to associate the DHCP options with.
	 * @param array $opt (Optional) An associative array of parameters that can have the following keys: <ul>
	 * 	<li><code>curlopts</code> - <code>array</code> - Optional - A set of values to pass directly into <code>curl_setopt()</code>, where the key is a pre-defined <code>CURLOPT_*</code> constant.</li>
	 * 	<li><code>returnCurlHandle</code> - <code>boolean</code> - Optional - A private toggle specifying that the cURL handle be returned rather than actually completing the request. This toggle is useful for manually managed batch requests.</li></ul>
	 * @return CFResponse A <CFResponse> object containing a parsed HTTP response.
	 */
	public function associate_dhcp_options($dhcp_options_id, $vpc_id, $opt = null)
	{
		if (!$opt) $opt = array();
		$opt['DhcpOptionsId'] = $dhcp_options_id;
		$opt['VpcId'] = $vpc_id;
		
		return $this->authenticate('AssociateDhcpOptions', $opt);
	}

	/**
	 * Associates a subnet with a route table. The subnet and route table must be in the same VPC.
	 * This association causes traffic originating from the subnet to be routed according to the
	 * routes in the route table. The action returns an association ID, which you need if you want to
	 * disassociate the route table from the subnet later. A route table can be associated with
	 * multiple subnets.
	 *  
	 * For more information about route tables, go to <a href=
	 * "http://docs.amazonwebservices.com/AmazonVPC/latest/UserGuide/VPC_Route_Tables.html">Route
	 * Tables</a> in the Amazon Virtual Private Cloud User Guide.
	 *
	 * @param string $subnet_id (Required) The ID of the subnet.
	 * @param string $route_table_id (Required) The ID of the route table.
	 * @param array $opt (Optional) An associative array of parameters that can have the following keys: <ul>
	 * 	<li><code>curlopts</code> - <code>array</code> - Optional - A set of values to pass directly into <code>curl_setopt()</code>, where the key is a pre-defined <code>CURLOPT_*</code> constant.</li>
	 * 	<li><code>returnCurlHandle</code> - <code>boolean</code> - Optional - A private toggle specifying that the cURL handle be returned rather than actually completing the request. This toggle is useful for manually managed batch requests.</li></ul>
	 * @return CFResponse A <CFResponse> object containing a parsed HTTP response.
	 */
	public function associate_route_table($subnet_id, $route_table_id, $opt = null)
	{
		if (!$opt) $opt = array();
		$opt['SubnetId'] = $subnet_id;
		$opt['RouteTableId'] = $route_table_id;
		
		return $this->authenticate('AssociateRouteTable', $opt);
	}

	/**
	 * Attaches an Internet gateway to a VPC, enabling connectivity between the Internet and the VPC.
	 * For more information about your VPC and Internet gateway, go to the Amazon Virtual Private
	 * Cloud User Guide.
	 *
	 * @param string $internet_gateway_id (Required) The ID of the Internet gateway to attach.
	 * @param string $vpc_id (Required) The ID of the VPC.
	 * @param array $opt (Optional) An associative array of parameters that can have the following keys: <ul>
	 * 	<li><code>curlopts</code> - <code>array</code> - Optional - A set of values to pass directly into <code>curl_setopt()</code>, where the key is a pre-defined <code>CURLOPT_*</code> constant.</li>
	 * 	<li><code>returnCurlHandle</code> - <code>boolean</code> - Optional - A private toggle specifying that the cURL handle be returned rather than actually completing the request. This toggle is useful for manually managed batch requests.</li></ul>
	 * @return CFResponse A <CFResponse> object containing a parsed HTTP response.
	 */
	public function attach_internet_gateway($internet_gateway_id, $vpc_id, $opt = null)
	{
		if (!$opt) $opt = array();
		$opt['InternetGatewayId'] = $internet_gateway_id;
		$opt['VpcId'] = $vpc_id;
		
		return $this->authenticate('AttachInternetGateway', $opt);
	}

	/**
	 * 
	 *
	 * @param string $network_interface_id (Required) 
	 * @param string $instance_id (Required) 
	 * @param integer $device_index (Required) 
	 * @param array $opt (Optional) An associative array of parameters that can have the following keys: <ul>
	 * 	<li><code>curlopts</code> - <code>array</code> - Optional - A set of values to pass directly into <code>curl_setopt()</code>, where the key is a pre-defined <code>CURLOPT_*</code> constant.</li>
	 * 	<li><code>returnCurlHandle</code> - <code>boolean</code> - Optional - A private toggle specifying that the cURL handle be returned rather than actually completing the request. This toggle is useful for manually managed batch requests.</li></ul>
	 * @return CFResponse A <CFResponse> object containing a parsed HTTP response.
	 */
	public function attach_network_interface($network_interface_id, $instance_id, $device_index, $opt = null)
	{
		if (!$opt) $opt = array();
		$opt['NetworkInterfaceId'] = $network_interface_id;
		$opt['InstanceId'] = $instance_id;
		$opt['DeviceIndex'] = $device_index;
		
		return $this->authenticate('AttachNetworkInterface', $opt);
	}

	/**
	 * Attach a previously created volume to a running instance.
	 *
	 * @param string $volume_id (Required) The ID of the Amazon EBS volume. The volume and instance must be within the same Availability Zone and the instance must be running.
	 * @param string $instance_id (Required) The ID of the instance to which the volume attaches. The volume and instance must be within the same Availability Zone and the instance must be running.
	 * @param string $device (Required) Specifies how the device is exposed to the instance (e.g., <code>/dev/sdh</code>).
	 * @param array $opt (Optional) An associative array of parameters that can have the following keys: <ul>
	 * 	<li><code>curlopts</code> - <code>array</code> - Optional - A set of values to pass directly into <code>curl_setopt()</code>, where the key is a pre-defined <code>CURLOPT_*</code> constant.</li>
	 * 	<li><code>returnCurlHandle</code> - <code>boolean</code> - Optional - A private toggle specifying that the cURL handle be returned rather than actually completing the request. This toggle is useful for manually managed batch requests.</li></ul>
	 * @return CFResponse A <CFResponse> object containing a parsed HTTP response.
	 */
	public function attach_volume($volume_id, $instance_id, $device, $opt = null)
	{
		if (!$opt) $opt = array();
		$opt['VolumeId'] = $volume_id;
		$opt['InstanceId'] = $instance_id;
		$opt['Device'] = $device;
		
		return $this->authenticate('AttachVolume', $opt);
	}

	/**
	 * Attaches a VPN gateway to a VPC. This is the last step required to get your VPC fully connected
	 * to your data center before launching instances in it. For more information, go to Process for
	 * Using Amazon VPC in the Amazon Virtual Private Cloud Developer Guide.
	 *
	 * @param string $vpn_gateway_id (Required) The ID of the VPN gateway to attach to the VPC.
	 * @param string $vpc_id (Required) The ID of the VPC to attach to the VPN gateway.
	 * @param array $opt (Optional) An associative array of parameters that can have the following keys: <ul>
	 * 	<li><code>curlopts</code> - <code>array</code> - Optional - A set of values to pass directly into <code>curl_setopt()</code>, where the key is a pre-defined <code>CURLOPT_*</code> constant.</li>
	 * 	<li><code>returnCurlHandle</code> - <code>boolean</code> - Optional - A private toggle specifying that the cURL handle be returned rather than actually completing the request. This toggle is useful for manually managed batch requests.</li></ul>
	 * @return CFResponse A <CFResponse> object containing a parsed HTTP response.
	 */
	public function attach_vpn_gateway($vpn_gateway_id, $vpc_id, $opt = null)
	{
		if (!$opt) $opt = array();
		$opt['VpnGatewayId'] = $vpn_gateway_id;
		$opt['VpcId'] = $vpc_id;
		
		return $this->authenticate('AttachVpnGateway', $opt);
	}

	/**
	 * This action applies only to security groups in a VPC; it's not supported for EC2 security
	 * groups. For information about Amazon Virtual Private Cloud and VPC security groups, go to the
	 * Amazon Virtual Private Cloud User Guide.
	 *  
	 * The action adds one or more egress rules to a VPC security group. Specifically, this permits
	 * instances in a security group to send traffic to either one or more destination CIDR IP address
	 * ranges, or to one or more destination security groups in the same VPC.
	 *  
	 * Each rule consists of the protocol (e.g., TCP), plus either a CIDR range, or a source group.
	 * For the TCP and UDP protocols, you must also specify the destination port or port range. For
	 * the ICMP protocol, you must also specify the ICMP type and code. You can use <code>-1</code> as
	 * a wildcard for the ICMP type or code.
	 *  
	 * Rule changes are propagated to instances within the security group as quickly as possible.
	 * However, a small delay might occur.
	 *  
	 * <strong>Important:</strong> For VPC security groups: You can have up to 50 rules total per
	 * group (covering both ingress and egress).
	 *
	 * @param string $group_id (Required) ID of the VPC security group to modify.
	 * @param array $opt (Optional) An associative array of parameters that can have the following keys: <ul>
	 * 	<li><code>IpPermissions</code> - <code>array</code> - Optional - List of IP permissions to authorize on the specified security group. Specifying permissions through IP permissions is the preferred way of authorizing permissions since it offers more flexibility and control. <ul>
	 * 		<li><code>x</code> - <code>array</code> - Optional - This represents a simple array index. <ul>
	 * 			<li><code>IpProtocol</code> - <code>string</code> - Optional - The IP protocol of this permission. Valid protocol values: <code>tcp</code>, <code>udp</code>, <code>icmp</code></li>
	 * 			<li><code>FromPort</code> - <code>integer</code> - Optional - Start of port range for the TCP and UDP protocols, or an ICMP type number. An ICMP type number of <code>-1</code> indicates a wildcard (i.e., any ICMP type number).</li>
	 * 			<li><code>ToPort</code> - <code>integer</code> - Optional - End of port range for the TCP and UDP protocols, or an ICMP code. An ICMP code of <code>-1</code> indicates a wildcard (i.e., any ICMP code).</li>
	 * 			<li><code>Groups</code> - <code>array</code> - Optional - The list of AWS user IDs and groups included in this permission. <ul>
	 * 				<li><code>x</code> - <code>array</code> - Optional - This represents a simple array index. <ul>
	 * 					<li><code>UserId</code> - <code>string</code> - Optional - The AWS user ID of an account.</li>
	 * 					<li><code>GroupName</code> - <code>string</code> - Optional - Name of the security group in the specified AWS account. Cannot be used when specifying a CIDR IP address range.</li>
	 * 					<li><code>GroupId</code> - <code>string</code> - Optional - ID of the security group in the specified AWS account. Cannot be used when specifying a CIDR IP address range.</li>
	 * 				</ul></li>
	 * 			</ul></li>
	 * 			<li><code>IpRanges</code> - <code>string|array</code> - Optional - The list of CIDR IP ranges included in this permission. Pass a string for a single value, or an indexed array for multiple values.</li>
	 * 		</ul></li>
	 * 	</ul></li>
	 * 	<li><code>curlopts</code> - <code>array</code> - Optional - A set of values to pass directly into <code>curl_setopt()</code>, where the key is a pre-defined <code>CURLOPT_*</code> constant.</li>
	 * 	<li><code>returnCurlHandle</code> - <code>boolean</code> - Optional - A private toggle specifying that the cURL handle be returned rather than actually completing the request. This toggle is useful for manually managed batch requests.</li></ul>
	 * @return CFResponse A <CFResponse> object containing a parsed HTTP response.
	 */
	public function authorize_security_group_egress($group_id, $opt = null)
	{
		if (!$opt) $opt = array();
		$opt['GroupId'] = $group_id;
		
		// Optional list + map
		if (isset($opt['IpPermissions']))
		{
			$opt = array_merge($opt, CFComplexType::map(array(
				'IpPermissions' => $opt['IpPermissions']
			)));
			unset($opt['IpPermissions']);
		}

		return $this->authenticate('AuthorizeSecurityGroupEgress', $opt);
	}

	/**
	 * The AuthorizeSecurityGroupIngress operation adds permissions to a security group.
	 *  
	 * Permissions are specified by the IP protocol (TCP, UDP or ICMP), the source of the request (by
	 * IP range or an Amazon EC2 user-group pair), the source and destination port ranges (for TCP and
	 * UDP), and the ICMP codes and types (for ICMP). When authorizing ICMP, <code>-1</code> can be
	 * used as a wildcard in the type and code fields.
	 *  
	 * Permission changes are propagated to instances within the security group as quickly as
	 * possible. However, depending on the number of instances, a small delay might occur.
	 *
	 * @param array $opt (Optional) An associative array of parameters that can have the following keys: <ul>
	 * 	<li><code>GroupName</code> - <code>string</code> - Optional - Name of the standard (EC2) security group to modify. The group must belong to your account. Can be used instead of GroupID for standard (EC2) security groups.</li>
	 * 	<li><code>GroupId</code> - <code>string</code> - Optional - ID of the standard (EC2) or VPC security group to modify. The group must belong to your account. Required for VPC security groups; can be used instead of GroupName for standard (EC2) security groups.</li>
	 * 	<li><code>IpPermissions</code> - <code>array</code> - Optional - List of IP permissions to authorize on the specified security group. Specifying permissions through IP permissions is the preferred way of authorizing permissions since it offers more flexibility and control. <ul>
	 * 		<li><code>x</code> - <code>array</code> - Optional - This represents a simple array index. <ul>
	 * 			<li><code>IpProtocol</code> - <code>string</code> - Optional - The IP protocol of this permission. Valid protocol values: <code>tcp</code>, <code>udp</code>, <code>icmp</code></li>
	 * 			<li><code>FromPort</code> - <code>integer</code> - Optional - Start of port range for the TCP and UDP protocols, or an ICMP type number. An ICMP type number of <code>-1</code> indicates a wildcard (i.e., any ICMP type number).</li>
	 * 			<li><code>ToPort</code> - <code>integer</code> - Optional - End of port range for the TCP and UDP protocols, or an ICMP code. An ICMP code of <code>-1</code> indicates a wildcard (i.e., any ICMP code).</li>
	 * 			<li><code>Groups</code> - <code>array</code> - Optional - The list of AWS user IDs and groups included in this permission. <ul>
	 * 				<li><code>x</code> - <code>array</code> - Optional - This represents a simple array index. <ul>
	 * 					<li><code>UserId</code> - <code>string</code> - Optional - The AWS user ID of an account.</li>
	 * 					<li><code>GroupName</code> - <code>string</code> - Optional - Name of the security group in the specified AWS account. Cannot be used when specifying a CIDR IP address range.</li>
	 * 					<li><code>GroupId</code> - <code>string</code> - Optional - ID of the security group in the specified AWS account. Cannot be used when specifying a CIDR IP address range.</li>
	 * 				</ul></li>
	 * 			</ul></li>
	 * 			<li><code>IpRanges</code> - <code>string|array</code> - Optional - The list of CIDR IP ranges included in this permission. Pass a string for a single value, or an indexed array for multiple values.</li>
	 * 		</ul></li>
	 * 	</ul></li>
	 * 	<li><code>curlopts</code> - <code>array</code> - Optional - A set of values to pass directly into <code>curl_setopt()</code>, where the key is a pre-defined <code>CURLOPT_*</code> constant.</li>
	 * 	<li><code>returnCurlHandle</code> - <code>boolean</code> - Optional - A private toggle specifying that the cURL handle be returned rather than actually completing the request. This toggle is useful for manually managed batch requests.</li></ul>
	 * @return CFResponse A <CFResponse> object containing a parsed HTTP response.
	 */
	public function authorize_security_group_ingress($opt = null)
	{
		if (!$opt) $opt = array();
				
		// Optional list + map
		if (isset($opt['IpPermissions']))
		{
			$opt = array_merge($opt, CFComplexType::map(array(
				'IpPermissions' => $opt['IpPermissions']
			)));
			unset($opt['IpPermissions']);
		}

		return $this->authenticate('AuthorizeSecurityGroupIngress', $opt);
	}

	/**
	 * The BundleInstance operation request that an instance is bundled the next time it boots. The
	 * bundling process creates a new image from a running instance and stores the AMI data in S3. Once
	 * bundled, the image must be registered in the normal way using the RegisterImage API.
	 *
	 * @param string $instance_id (Required) The ID of the instance to bundle.
	 * @param array $policy (Required) The details of S3 storage for bundling a Windows instance. Takes an associative array of parameters that can have the following keys: <ul>
	 * 	<li><code>Bucket</code> - <code>string</code> - Optional - The bucket in which to store the AMI. You can specify a bucket that you already own or a new bucket that Amazon EC2 creates on your behalf. If you specify a bucket that belongs to someone else, Amazon EC2 returns an error.</li>
	 * 	<li><code>Prefix</code> - <code>string</code> - Optional - The prefix to use when storing the AMI in S3.</li>
	 * 	<li><code>AWSAccessKeyId</code> - <code>string</code> - Optional - The Access Key ID of the owner of the Amazon S3 bucket. Use the <CFPolicy::get_key()> method of a <CFPolicy> instance.</li>
	 * 	<li><code>UploadPolicy</code> - <code>string</code> - Optional - A Base64-encoded Amazon S3 upload policy that gives Amazon EC2 permission to upload items into Amazon S3 on the user's behalf. Use the <CFPolicy::get_policy()> method of a <CFPolicy> instance.</li>
	 * 	<li><code>UploadPolicySignature</code> - <code>string</code> - Optional - The signature of the Ba